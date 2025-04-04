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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

final class DetachUsersAction extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly CommandBusInterface $commandBus
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
            ->authorize(fn (): bool => Gate::allows('userTenantDetachAny', [$this->user::class, $tenant]))
            ->modalHeading(fn (Collection $records): string => Lang::choice('tenant.pages.users.detach_multi.title', $records->count(), [
                'number' => $records->count(),
            ]))
            ->using(function (Collection $records) use ($tenant): int {
                $records = $records->filter(fn (User $user) => Gate::allows('userTenantDetach', [$user, $tenant]));

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
