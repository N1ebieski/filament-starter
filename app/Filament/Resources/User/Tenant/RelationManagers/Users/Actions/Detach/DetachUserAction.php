<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Detach;

use App\Commands\CommandBusInterface;
use App\Commands\User\Tenants\Detach\DetachCommand;
use App\Filament\Actions\Action;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Filament\Tables\Actions\DetachAction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;

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
            ->authorize(fn (User $user): bool => Gate::allows('userTenantDetach', [$user, $tenant]))
            ->modalHeading(fn (User $record): string => Lang::string('tenant.pages.users.detach.title', [
                'name' => $record->name,
            ]))
            ->using(fn (User $record): bool => $this->commandBus->execute(new DetachCommand(
                tenant: $tenant,
                user: $record
            )))
            ->successNotificationTitle(fn (User $record): string => Lang::string('tenant.messages.users.detach.success', [
                'name' => $record->name,
            ]));
    }
}
