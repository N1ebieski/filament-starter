<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions;

use App\Models\User\User;
use App\Models\Tenant\Tenant;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Lang;
use App\Commands\CommandBusInterface;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DetachBulkAction;
use App\Commands\User\Tenants\DetachMany\DetachManyCommand;

final class DetachUsersAction extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public static function make(Tenant $tenant): DetachBulkAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction($tenant);
    }

    public function getAction(Tenant $tenant): DetachBulkAction
    {
        return DetachBulkAction::make()
            ->hidden(function (Guard $guard) use ($tenant): bool {
                /** @var User|null */
                $user = $guard->user();

                return !$user?->can('tenantDetachAny', [$this->user::class, $tenant]);
            })
            ->modalHeading(function (Collection $records): string {
                return Lang::choice('tenant.pages.users.detach_multi.title', $records->count(), [
                    'number' => $records->count()
                ]);
            })
            ->using(function (Collection $records, Guard $guard) use ($tenant): int {
                $records = $records->filter(function (User $record) use ($guard, $tenant): bool {
                    /** @var User|null */
                    $user = $guard->user();

                    return $user?->can('tenantDetach', [$record, $tenant]);
                });

                return $this->commandBus->execute(new DetachManyCommand(
                    tenant: $tenant,
                    users: $records
                ));
            })
            ->successNotificationTitle(function (Collection $records): string {
                return Lang::choice('tenant.messages.users.detach_multi.success', $records->count(), [
                    'number' => $records->count()
                ]);
            });
    }
}
