<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions\Edit;

use App\Commands\CommandBusInterface;
use App\Commands\User\Edit\EditCommand;
use App\Filament\Actions\Action;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\ValueObjects\Role\Name\DefaultName;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Exists;

final class EditUserAction extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly Role $role,
        private readonly CommandBusInterface $commandBus
    ) {}

    public static function make(): EditAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction();
    }

    public function makeAction(): EditAction
    {
        return EditAction::make()
            ->authorize(fn (User $user): bool => Gate::allows('adminUpdate', $user))
            ->modalHeading(fn (User $record): string => Lang::string('user.pages.edit.title', [
                'name' => $record->name,
            ]))
            ->mutateRecordDataUsing(fn (array $data, User $record): array => [
                ...$data,
                'name' => $record->name->value,
                'roles' => $record->roles->pluck('id')->toArray(),
                'email' => $record->email->value,
            ])
            ->form([
                TextInput::make('name')
                    ->label(Lang::string('user.name.label'))
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique($this->user->getTable(), 'name', ignoreRecord: true),

                TextInput::make('email')
                    ->label(Lang::string('user.email.label'))
                    ->required()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password',
                    ])
                    ->string()
                    ->email()
                    ->maxLength(255)
                    ->unique($this->user->getTable(), 'email', ignoreRecord: true),

                TextInput::make('password')
                    ->label(Lang::string('user.password.label'))
                    ->password()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password',
                    ])
                    ->revealable()
                    ->nullable()
                    ->string()
                    ->minLength(8)
                    ->maxLength(255)
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label(Lang::string('user.password_confirmation.label'))
                    ->password()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password',
                    ])
                    ->revealable()
                    ->nullable()
                    ->string()
                    ->minLength(8)
                    ->maxLength(255),

                Select::make('roles')
                    ->label(Lang::string('user.roles.label'))
                    ->multiple()
                    ->relationship($this->role->getTable(), 'name', function (Builder $query) {
                        /** @var Builder<Role> $query */
                        return $query->whereNot('name', DefaultName::SuperAdmin);
                    })
                    ->preload()
                    ->dehydrated(true)
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Role $record) => $record->name->value)
                    ->exists($this->role->getTable(), 'id', fn (Exists $rule) => $rule->whereNot('name', DefaultName::SuperAdmin)),
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->mutateFormDataUsing(function (array $data): array {
                if (is_null($data['password'])) {
                    unset($data['password']);
                }

                return $data;
            })
            ->using(fn (array $data, User $record): User => $this->commandBus->execute(EditCommand::from([
                ...$data,
                'user' => $record,
            ])))
            ->successNotificationTitle(fn (User $record): string => Lang::string('user.messages.edit.success', [
                'name' => $record->name,
            ]));
    }
}
