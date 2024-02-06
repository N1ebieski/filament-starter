<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions;

use App\Models\User\User;
use App\Commands\CommandBus;
use App\Models\Tenant\Tenant;
use App\Filament\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DetachBulkAction;
use App\Commands\User\Tenants\DetachMulti\DetachMultiCommand;

final class DetachUsers extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly CommandBus $commandBus
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
            ->modalHeading(function (Collection $records): string {
                return Lang::choice('tenant.pages.users.detach_multi.title', $records->count(), [
                    'number' => $records->count()
                ]);
            })
            ->using(function (Collection $records) use ($tenant): int {
                return $this->commandBus->execute(new DetachMultiCommand(
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
