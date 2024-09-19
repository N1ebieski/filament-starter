<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\EditPermissions;

use App\Commands\CommandBusInterface;
use App\Commands\User\Tenants\EditPermissions\EditPermissionsCommand;
use App\Filament\Actions\Action;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\HasPermissions;
use App\Models\Permission\Permission;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Queries\QueryBusInterface;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Exists;

final class EditPermissionsAction extends Action
{
    use HasPermissions;

    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly Permission $permission
    ) {}

    public static function make(Tenant $tenant): EditAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction($tenant);
    }

    public function makeAction(Tenant $tenant): EditAction
    {
        return EditAction::make()
            ->hidden(function (User $record, Guard $guard) use ($tenant): bool {
                /** @var User|null */
                $user = $guard->user();

                return ! $user?->can('tenantUpdatePermissions', [$record, $tenant]);
            })
            ->icon('heroicon-s-shield-check')
            ->label(Lang::get('user.permissions.label'))
            ->modalHeading(fn (User $record): string => Lang::get('tenant.pages.users.edit_permissions.title', [
                'name' => $record->name,
            ]))
            ->mutateRecordDataUsing(function (array $data, User $record): array {
                $data['permissions'] = $record->tenantPermissions->pluck('id')->toArray();

                return $data;
            })
            ->form([
                Select::make('permissions')
                    ->label(Lang::get('user.permissions.label'))
                    ->options($this->getGroupedPermissions()->toArray())
                    ->searchable()
                    ->multiple()
                    ->exists(
                        $this->permission->getTable(),
                        'id',
                        function (Exists $rule): Exists {
                            return $rule->where(function (Builder $builder): Builder {
                                return $builder->where('name', 'like', 'tenant.%');
                            });
                        }
                    ),
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data, User $record) use ($tenant): User {
                return $this->commandBus->execute(EditPermissionsCommand::from([
                    ...$data,
                    'tenant' => $tenant,
                    'user' => $record,
                ]));
            })
            ->successNotificationTitle(function (User $record): string {
                return Lang::get('tenant.messages.users.edit_permissions.success', [
                    'name' => $record->name,
                ]);
            });
    }
}
