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
use Filament\Tables\Actions\DetachAction;
use App\Commands\User\Tenants\Detach\DetachCommand;

final class DetachUserAction extends Action
{
    public function __construct(
        private readonly User $user,
        private readonly CommandBusInterface $commandBus
    ) {
    }

    public static function make(Tenant $tenant): DetachAction
    {
        /** @var static */
        $static = App::make(static::class);

        return $static->getAction($tenant);
    }

    public function getAction(Tenant $tenant): DetachAction
    {
        return DetachAction::make()
            ->hidden(function (User $record, Guard $guard) use ($tenant): bool {
                /** @var User|null */
                $user = $guard->user();

                return !$user?->can('tenantDetach', [$record, $tenant]);
            })
            ->modalHeading(function (User $record): string {
                return Lang::get('tenant.pages.users.detach.title', [
                    'name' => $record->name
                ]);
            })
            ->using(function (User $record) use ($tenant): User {
                return $this->commandBus->execute(new DetachCommand(
                    tenant: $tenant,
                    user: $record
                ));
            })
            ->successNotificationTitle(function (User $record): string {
                return Lang::get('tenant.messages.users.detach.success', [
                    'name' => $record->name
                ]);
            });
    }
}
