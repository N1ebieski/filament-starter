<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions;

use App\Models\Role\Role;
use App\Models\User\User;
use App\Queries\QueryBus;
use App\Commands\CommandBus;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Filament\Forms\Components\Select;
use App\Commands\User\Edit\EditCommand;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\TextInput;

final class EditUser
{
    public function __construct(
        private readonly User $user,
        private readonly Role $role,
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus
    ) {
    }

    public static function make(): EditAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction();
    }

    public function getAction(): EditAction
    {
        return EditAction::make()
            ->modalHeading(fn (User $record): string => Lang::get('user.pages.edit.title', [
                'name' => $record->name
            ]))
            ->mutateRecordDataUsing(function (array $data, User $record): array {
                $data['roles'] = $record->roles->pluck('id')->toArray();

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
                    ->relationship($this->role->getTable(), 'name')
                    ->preload()
                    ->dehydrated(true)
                    ->required()
                    ->exists($this->role->getTable(), 'id')
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data, User $record): User {
                return $this->commandBus->execute(
                    new EditCommand(
                        user: $record,
                        name: $data['name'],
                        email: $data['email'],
                        password: $data['password'] ?? $record->password,
                        roles: $this->role->newQuery()->findMany($data['roles'])
                    )
                );
            })
            ->successNotificationTitle(fn (User $record): string => Lang::get('user.messages.edit', [
                'name' => $record->name
            ]));
    }
}
