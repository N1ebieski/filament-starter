<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Role\Role;
use App\Queries\QueryBus;
use App\Commands\CommandBus;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use App\Models\Permission\Permission;
use Filament\Forms\Components\Select;
use App\ValueObjects\Role\DefaultName;
use App\Commands\Role\Edit\EditCommand;
use Filament\Tables\Actions\EditAction;
use Illuminate\Validation\Rules\Exists;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Database\Query\Builder;
use App\Filament\Resources\Admin\Role\Actions\HasPermissions;

final class EditRole
{
    use HasPermissions;

    public function __construct(
        private readonly Role $role,
        private readonly Permission $permission,
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus
    ) {
    }

    public static function make(): EditAction
    {
        /** @var self */
        $static = App::make(static::class);

        return $static->get();
    }

    public function get(): EditAction
    {
        return EditAction::make()
            ->modalHeading(fn (Role $record) => Lang::get('role.pages.edit.title', ['name' => $record->name->value]))
            ->mutateRecordDataUsing(function (array $data, Role $record): array {
                $data['name'] = $data['name']->value;
                $data['permissions'] = $record->permissions->pluck('id')->toArray();

                return $data;
            })
            ->form([
                TextInput::make('name')
                    ->label(Lang::get('role.name.label'))
                    ->required()
                    ->disabled(fn (Role $record) => $record->name->isDefault())
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique($this->role->getTable(), 'name', ignoreRecord: true),

                Select::make('permissions')
                    ->label(Lang::get('role.permissions.label'))
                    ->options(fn (Role $record) => $this->getGroupedPermissions($record)->toArray())
                    ->searchable()
                    ->multiple()
                    ->required()
                    ->exists($this->permission->getTable(), 'id', function (Exists $rule, Role $record) {
                        return $rule->when(
                            $record->name->isEqualsDefault(DefaultName::USER),
                            function (Exists $rule) {
                                return $rule->where(function (Builder $builder) {
                                    return $builder->where('name', 'like', 'web.%')
                                        ->orWhere('name', 'like', 'api.%');
                                });
                            }
                        )
                        ->when(
                            $record->name->isEqualsDefault(DefaultName::API),
                            function (Exists $rule) {
                                return $rule->where(function (Builder $builder) {
                                    return $builder->where('name', 'like', 'api.%');
                                });
                            }
                        );
                    })
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data, Role $record): Role {
                return $this->commandBus->execute(new EditCommand(
                    role: $record,
                    name: $data['name'],
                    permissions: $this->permission->newQuery()->findMany($data['permissions'])
                ));
            })
            ->successNotificationTitle(fn (Role $record) => Lang::get('role.messages.edit', ['name' => $record->name]));
    }
}
