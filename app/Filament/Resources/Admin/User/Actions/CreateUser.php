<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User\Actions;

use App\Models\Role\Role;
use App\Models\User\User;
use App\Queries\QueryBus;
use App\Commands\CommandBus;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Commands\User\Create\CreateCommand;
use Illuminate\Database\Eloquent\Collection;

final class CreateUser
{
    public function __construct(
        private readonly User $user,
        private readonly Role $role,
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus
    ) {
    }

    public static function make(Collection $roles = new Collection()): CreateAction
    {
        /** @var self */
        $static = App::make(static::class);

        return $static->get(roles: $roles);
    }

    public function get(Collection $roles = new Collection()): CreateAction
    {
        return CreateAction::make()
            ->model($this->user::class)
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::get('user.pages.create.title'))
            ->fillForm(function (array $data) use ($roles) {
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
                    ->required()
                    ->string()
                    ->email()
                    ->maxLength(255)
                    ->unique($this->user->getTable(), 'email'),

                TextInput::make('password')
                    ->label(Lang::get('user.password.label'))
                    ->password()
                    ->revealable()
                    ->required()
                    ->string()
                    ->minLength(8)
                    ->maxLength(255)
                    ->confirmed(),

                TextInput::make('password_confirmation')
                    ->label(Lang::get('user.password_confirmation.label'))
                    ->password()
                    ->revealable()
                    ->required()
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
            ->using(function (array $data): User {
                return $this->commandBus->execute(
                    new CreateCommand(
                        name: $data['name'],
                        email: $data['email'],
                        password: $data['password'],
                        roles: $this->role->newQuery()->findMany($data['roles'])
                    )
                );
            })
            ->successNotificationTitle(fn (User $record) => Lang::get('user.messages.create', ['name' => $record->name]));
    }
}
