<?php

declare(strict_types=1);

namespace App\Observers\Tenant;

use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Observers\Observer;
use Illuminate\Database\ConnectionInterface as DB;

class TenantObserver extends Observer
{
    public function __construct(private readonly DB $db) {}

    public function pivotDetaching(Tenant $tenant, string $relationName): void
    {
        $this->db->transaction(function () use ($tenant, $relationName): void {
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
        });
    }

    public function deleting(Tenant $tenant): void
    {
        $this->db->transaction(function () use ($tenant): void {
            $tenant->users()->detach();
        });
    }
}
