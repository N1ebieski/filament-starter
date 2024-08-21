<?php

declare(strict_types=1);

namespace App\Observers\Tenant;

use App\Models\User\User;
use App\Models\Tenant\Tenant;

class TenantObserver
{
    public function pivotDetaching(Tenant $tenant, string $relationName): void
    {
        if ($relationName === $tenant->users()->getRelationName()) {
            /** @var array<User> */
            $users = $tenant->users()->with('tenantPermissions')->get();

            foreach ($users as $user) {
                $user->fireModelEvent(
                    event: 'pivotDetaching',
                    relationName: $user->tenants()->getRelationName(),
                );
            }
        }
    }

    public function deleting(Tenant $tenant): void
    {
        $tenant->users()->detach();
    }
}
