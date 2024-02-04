<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Role\Role;
use App\Queries\QueryBus;
use App\Commands\CommandBus;
use App\Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use App\Models\Permission\Permission;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Commands\Role\Create\CreateCommand;
use App\Filament\Resources\Admin\Role\Actions\HasPermissions;

final class CreateRole extends Action
{
    use HasPermissions;

    public function __construct(
        private readonly Role $role,
        private readonly Permission $permission,
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus
    ) {
    }

    public static function make(): CreateAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction();
    }

    public function getAction(): CreateAction
    {
        return CreateAction::make()
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::get('role.pages.create.title'))
            ->form([
                TextInput::make('name')
                    ->label(Lang::get('role.name.label'))
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique($this->role->getTable(), 'name'),

                Select::make('permissions')
                    ->label(Lang::get('role.permissions.label'))
                    ->options($this->getGroupedPermissions()->toArray())
                    ->searchable()
                    ->multiple()
                    ->required()
                    ->exists($this->permission->getTable(), 'id')
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data): Role {
                return $this->commandBus->execute(new CreateCommand(
                    name: $data['name'],
                    permissions: $this->permission->newQuery()->findMany($data['permissions'])
                ));
            })
            ->successNotificationTitle(fn (Role $record): string => Lang::get('role.messages.create', [
                'name' => $record->name
            ]));
    }
}
