<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Attach;

use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Filament\Actions\Action;
use App\Queries\QueryBusInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use App\Commands\CommandBusInterface;
use App\Models\Permission\Permission;
use Filament\Forms\Components\Select;
use Illuminate\Validation\Rules\Exists;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Contracts\Database\Query\Builder;
use App\Commands\User\Tenants\Attach\AttachCommand;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\HasPermissions;

final class AttachUserAction extends Action
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

        return $static->makeAction($tenant);
    }

    public function makeAction(Tenant $tenant): AttachAction
    {
        return AttachAction::make()
            ->hidden(function (Guard $guard) use ($tenant): bool {
                /** @var User|null */
                $user = $guard->user();

                return !$user?->can('tenantAttach', [$this->user::class, $tenant]);
            })
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::get('tenant.pages.users.attach.title'))
            ->form(fn (AttachAction $action): array => [
                $action->recordTitle(fn (User $record): string => $record->name->value)
                    ->getRecordSelect()
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
            ->using(function (array $data) use ($tenant): bool {
                return $this->commandBus->execute(AttachCommand::from([
                    ...$data,
                    'tenant' => $tenant,
                    'user' => $this->user->find($data['recordId'])
                ]));
            })
            ->successNotificationTitle(function (User $record): string {
                return Lang::get('tenant.messages.users.attach.success', [
                    'name' => $record->name
                ]);
            });
    }
}
