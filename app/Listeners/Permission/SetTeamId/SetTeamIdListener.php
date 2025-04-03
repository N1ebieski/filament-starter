<?php

declare(strict_types=1);

namespace App\Listeners\Permission\SetTeamId;

use App\Listeners\Listener;
use App\Models\Tenant\Tenant;
use Filament\Events\TenantSet;
use Spatie\Permission\PermissionRegistrar;

final class SetTeamIdListener extends Listener
{
    public function __construct(
        private readonly PermissionRegistrar $permissionRegistrar
    ) {}

    public function handle(TenantSet $event): void
    {
        /** @var Tenant */
        $tenant = $event->getTenant();

        $this->permissionRegistrar->setPermissionsTeamId($tenant->id);
    }
}
