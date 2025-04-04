<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\Permission\Permission;
use App\Models\User\User;
use App\Observers\Observer;
use Illuminate\Database\ConnectionInterface as DB;

class UserObserver extends Observer
{
    public function __construct(private readonly DB $db) {}

    public function pivotDetaching(User $user, string $relationName): void
    {
        $this->db->transaction(function () use ($user, $relationName): void {
            if ($relationName === $user->tenants()->getRelationName()) {
                $user->revokeTenantPermissionTo(
                    $user->tenantPermissions
                        ->map(fn (Permission $permission): string => $permission->name->value)
                        ->toArray()
                );
            }
        });
    }

    public function deleting(User $user): void
    {
        $this->db->transaction(function () use ($user): void {
            $user->breezySessions()->delete();

            $user->notifications()->delete();

            $user->tokens()->delete();

            $user->ownedTenants()->delete();

            $user->permissions()->detach();

            $user->roles()->detach();
        });
    }
}
