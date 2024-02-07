<?php

declare(strict_types=1);

namespace App\Listeners\Permission\SetTeamId;

use Filament\Events\TenantSet;
use Illuminate\Events\Dispatcher;
use Spatie\Permission\PermissionRegistrar;

class SetTeamIdHandler
{
    public function __construct(
        private readonly PermissionRegistrar $permissionRegistrar
    ) {
    }

    public function handle(TenantSet $event): void
    {
        /** @var Tenant */
        $tenant = $event->getTenant();

        $this->permissionRegistrar->setPermissionsTeamId($tenant->id);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            TenantSet::class => 'handle',
        ];
    }
}
