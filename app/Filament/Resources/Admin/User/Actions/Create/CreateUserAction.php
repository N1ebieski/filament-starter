<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions\Create;

use App\Commands\CommandBusInterface;
use App\Commands\User\Create\CreateCommand;
use App\Filament\Actions\Action;
use App\Models\Role\Role;
use App\Models\User\User;
use App\ValueObjects\Role\Name\DefaultName;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Exists;

final class CreateUserAction extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly Role $role,
        private readonly CommandBusInterface $commandBus
    ) {}

    public static function make(Collection $roles = new Collection): CreateAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction(roles: $roles);
    }

    public function makeAction(Collection $roles = new Collection): CreateAction
    {
        return CreateAction::make()
            ->model($this->user::class)
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::get('user.pages.create.title'))
            ->fillForm(function (array $data) use ($roles): array {
                $data['roles'] = $roles->pluck('id')->toArray();

                return $data;
            })
            ->form([
                TextInput::make('name')
                    ->label(Lang::get('user.name.label'))
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique($this->user->getTable(), 'name'),

                TextInput::make('email')
                    ->label(Lang::get('user.email.label'))
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password',
                    ])
                    ->required()
                    ->string()
                    ->email()
                    ->maxLength(255)
                    ->unique($this->user->getTable(), 'email'),

                TextInput::make('password')
                    ->label(Lang::get('user.password.label'))
                    ->password()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password',
                    ])
                    ->revealable()
                    ->required()
                    ->string()
                    ->minLength(8)
                    ->maxLength(255)
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label(Lang::get('user.password_confirmation.label'))
                    ->password()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password',
                    ])
                    ->revealable()
                    ->required()
                    ->string()
                    ->minLength(8)
                    ->maxLength(255),

                Select::make('roles')
                    ->label(Lang::get('user.roles.label'))
                    ->multiple()
                    ->relationship($this->role->getTable(), 'name', function (Builder $builder) {
                        /** @var Builder<Role> $builder */
                        return $builder->whereNot('name', DefaultName::SuperAdmin);
                    })
                    ->preload()
                    ->dehydrated(true)
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Role $record) => $record->name->value)
                    ->exists($this->role->getTable(), 'id', function (Exists $rule) {
                        return $rule->whereNot('name', DefaultName::SuperAdmin);
                    }),
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data): User {
                return $this->commandBus->execute(CreateCommand::from($data));
            })
            ->successNotificationTitle(fn (User $record): string => Lang::get('user.messages.create.success', [
                'name' => $record->name,
            ]));
    }
}
