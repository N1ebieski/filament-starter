<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions\Create;

use App\Commands\CommandBusInterface;
use App\Commands\Role\Create\CreateCommand;
use App\Filament\Actions\Action;
use App\Filament\Resources\Admin\Role\Actions\HasPermissions;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Queries\QueryBusInterface;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\App;

final readonly class CreateRoleAction extends Action
{
    use HasPermissions;

    public function __construct(
        private Role $role,
        private Permission $permission,
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus
    ) {}

    public static function make(): CreateAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction();
    }

    public function makeAction(): CreateAction
    {
        return CreateAction::make()
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::string('role.pages.create.title'))
            ->form([
                TextInput::make('name')
                    ->label(Lang::string('role.name.label'))
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique($this->role->getTable(), 'name'),

                Select::make('permissions')
                    ->label(Lang::string('role.permissions.label'))
                    ->options($this->getGroupedPermissions()->toArray())
                    ->searchable()
                    ->multiple()
                    ->required()
                    ->exists($this->permission->getTable(), 'id'),
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(fn (array $data): Role => $this->commandBus->execute(CreateCommand::from($data)))
            ->successNotificationTitle(fn (Role $record): string => Lang::string('role.messages.create.success', [
                'name' => $record->name,
            ]));
    }
}
