<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions\Edit;

use App\Models\Role\Role;
use App\Models\User\User;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use App\Commands\CommandBusInterface;
use Filament\Forms\Components\Select;
use App\Commands\User\Edit\EditCommand;
use Filament\Tables\Actions\EditAction;
use Illuminate\Validation\Rules\Exists;
use Filament\Forms\Components\TextInput;
use App\ValueObjects\Role\Name\DefaultName;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class EditUserAction extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly Role $role,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public static function make(): EditAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->makeAction();
    }

    public function makeAction(): EditAction
    {
        return EditAction::make()
            ->modalHeading(fn (User $record): string => Lang::get('user.pages.edit.title', [
                'name' => $record->name
            ]))
            ->mutateRecordDataUsing(function (array $data, User $record): array {
                $data = [
                    ...$data,
                    'name' => $record->name->value,
                    'roles' => $record->roles->pluck('id')->toArray(),
                    'email' => $record->email->value
                ];

                return $data;
            })
            ->form([
                TextInput::make('name')
                    ->label(Lang::get('user.name.label'))
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique($this->user->getTable(), 'name', ignoreRecord: true),

                TextInput::make('email')
                    ->label(Lang::get('user.email.label'))
                    ->required()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password'
                    ])
                    ->string()
                    ->email()
                    ->maxLength(255)
                    ->unique($this->user->getTable(), 'email', ignoreRecord: true),

                TextInput::make('password')
                    ->label(Lang::get('user.password.label'))
                    ->password()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password'
                    ])
                    ->revealable()
                    ->nullable()
                    ->string()
                    ->minLength(8)
                    ->maxLength(255)
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label(Lang::get('user.password_confirmation.label'))
                    ->password()
                    ->extraInputAttributes([
                        'autocomplete' => 'new-password'
                    ])
                    ->revealable()
                    ->nullable()
                    ->string()
                    ->minLength(8)
                    ->maxLength(255),

                Select::make('roles')
                    ->label(Lang::get('user.roles.label'))
                    ->multiple()
                    ->relationship($this->role->getTable(), 'name', function (Builder $query) {
                        return $query->whereNot('name', DefaultName::SuperAdmin);
                    })
                    ->preload()
                    ->dehydrated(true)
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Role $record) => $record->name->value)
                    ->exists($this->role->getTable(), 'id', function (Exists $rule) {
                        return $rule->whereNot('name', DefaultName::SuperAdmin);
                    })
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->mutateFormDataUsing(function (array $data): array {
                if (is_null($data['password'])) {
                    unset($data['password']);
                }

                return $data;
            })
            ->using(function (array $data, User $record): User {
                return $this->commandBus->execute(EditCommand::from([
                    ...$data,
                    'user' => $record
                ]));
            })
            ->successNotificationTitle(fn (User $record): string => Lang::get('user.messages.edit.success', [
                'name' => $record->name
            ]));
    }
}
