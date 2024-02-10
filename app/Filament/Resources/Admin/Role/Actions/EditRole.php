<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Actions;

use App\Models\Role\Role;
use App\Queries\QueryBus;
use App\Commands\CommandBus;
use App\Filament\Actions\Action;
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

final class EditRole extends Action
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
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction();
    }

    public function getAction(): EditAction
    {
        return EditAction::make()
            ->modalHeading(fn (Role $record): string => Lang::get('role.pages.edit.title', [
                'name' => $record->name->value
            ]))
            ->mutateRecordDataUsing(function (array $data, Role $record): array {
                $data['name'] = $data['name']->value;
                $data['permissions'] = $record->permissions->pluck('id')->toArray();

                return $data;
            })
            ->form([
                TextInput::make('name')
                    ->label(Lang::get('role.name.label'))
                    ->required()
                    ->disabled(fn (Role $record): bool => $record->name->isDefault())
                    ->string()
                    ->minLength(3)
                    ->maxLength(255)
                    ->unique($this->role->getTable(), 'name', ignoreRecord: true),

                Select::make('permissions')
                    ->label(Lang::get('role.permissions.label'))
                    ->options(fn (Role $record): array => $this->getGroupedPermissions($record)->toArray())
                    ->searchable()
                    ->multiple()
                    ->required()
                    ->exists(
                        $this->permission->getTable(),
                        'id',
                        function (Exists $rule, Role $record): Exists {
                            return $rule->when(
                                $record->name->isEqualsDefault(DefaultName::User),
                                function (Exists $rule): Exists {
                                    return $rule->where(function (Builder $builder): Builder {
                                        return $builder->where('name', 'like', 'web.%')
                                            ->orWhere('name', 'like', 'api.%');
                                    });
                                }
                            )
                            ->when(
                                $record->name->isEqualsDefault(DefaultName::Api),
                                function (Exists $rule): Exists {
                                    return $rule->where(function (Builder $builder): Builder {
                                        return $builder->where('name', 'like', 'api.%');
                                    });
                                }
                            );
                        }
                    )
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data, Role $record): Role {
                return $this->commandBus->execute(new EditCommand(
                    role: $record,
                    name: $data['name'] ?? $record->name->value,
                    permissions: $this->permission->newQuery()->findMany($data['permissions'])
                ));
            })
            ->successNotificationTitle(fn (Role $record): string => Lang::get('role.messages.edit.success', [
                'name' => $record->name
            ]));
    }
}
