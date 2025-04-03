<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\DetachMany;

use App\Commands\CommandBusInterface;
use App\Commands\User\Tenants\DetachMany\DetachManyCommand;
use App\Filament\Actions\Action;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Filament\Tables\Actions\DetachBulkAction;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

final readonly class DetachUsersAction extends Action
{
    public function __construct(
        private User $user,
        private CommandBusInterface $commandBus
    ) {}

    public static function make(Tenant $tenant): DetachBulkAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction($tenant);
    }

    public function makeAction(Tenant $tenant): DetachBulkAction
    {
        return DetachBulkAction::make()
            ->hidden(function (Guard $guard) use ($tenant): bool {
                /** @var User|null */
                $user = $guard->user();

                return ! $user?->can('tenantDetachAny', [$this->user::class, $tenant]);
            })
            ->modalHeading(fn (Collection $records): string => Lang::choice('tenant.pages.users.detach_multi.title', $records->count(), [
                'number' => $records->count(),
            ]))
            ->using(function (Collection $records, Guard $guard) use ($tenant): int {
                $records = $records->filter(function (User $record) use ($guard, $tenant): bool {
                    /** @var User|null */
                    $user = $guard->user();

                    return $user?->can('tenantDetach', [$record, $tenant]) ?? false;
                });

                return $this->commandBus->execute(new DetachManyCommand(
                    tenant: $tenant,
                    users: $records
                ));
            })
            ->successNotificationTitle(fn (Collection $records): string => Lang::choice('tenant.messages.users.detach_multi.success', $records->count(), [
                'number' => $records->count(),
            ]));
    }
}
