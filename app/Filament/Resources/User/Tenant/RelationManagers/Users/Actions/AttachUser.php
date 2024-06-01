<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions;

use App\Models\User\User;
use App\Queries\QueryBusInterface;
use App\Commands\CommandBusInterface;
use App\Models\Tenant\Tenant;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use App\Models\Permission\Permission;
use Filament\Forms\Components\Select;
use Illuminate\Validation\Rules\Exists;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Contracts\Database\Query\Builder;
use App\Commands\User\Tenants\Attach\AttachCommand;

final class AttachUser extends Action
{
    use HasPermissions;

    public function __construct(
        private readonly User $user,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly Permission $permission
    ) {
    }

    public static function make(Tenant $tenant): AttachAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction($tenant);
    }

    public function getAction(Tenant $tenant): AttachAction
    {
        return AttachAction::make()
            ->hidden(function (Guard $guard) use ($tenant): bool {
                return !$guard->user()?->can('tenantAttach', [$this->user::class, $tenant]);
            })
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::get('tenant.pages.users.attach.title'))
            ->form(fn (AttachAction $action): array => [
                $action->getRecordSelect()
                    ->label(Lang::get('user.name.label'))
                    ->hiddenLabel(false),

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
                    )
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(function (array $data) use ($tenant): User {
                return $this->commandBus->execute(new AttachCommand(
                    tenant: $tenant,
                    user: $this->user->find($data['recordId']),
                    permissions: $this->permission->newQuery()->findMany($data['permissions'])
                ));
            })
            ->successNotificationTitle(function (User $record): string {
                return Lang::get('tenant.messages.users.attach.success', [
                    'name' => $record->name
                ]);
            });
    }
}
