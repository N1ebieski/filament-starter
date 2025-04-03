<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Detach;

use App\Commands\CommandBusInterface;
use App\Commands\User\Tenants\Detach\DetachCommand;
use App\Filament\Actions\Action;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use Filament\Tables\Actions\DetachAction;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;

final class DetachUserAction extends Action
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {}

    public static function make(Tenant $tenant): DetachAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction($tenant);
    }

    public function makeAction(Tenant $tenant): DetachAction
    {
        return DetachAction::make()
            ->hidden(function (User $record, Guard $guard) use ($tenant): bool {
                /** @var User|null */
                $user = $guard->user();

                return ! $user?->can('tenantDetach', [$record, $tenant]);
            })
            ->modalHeading(fn (User $record): string => Lang::get('tenant.pages.users.detach.title', [
                'name' => $record->name,
            ]))
            ->using(fn (User $record): bool => $this->commandBus->execute(new DetachCommand(
                tenant: $tenant,
                user: $record
            )))
            ->successNotificationTitle(fn (User $record): string => Lang::get('tenant.messages.users.detach.success', [
                'name' => $record->name,
            ]));
    }
}
