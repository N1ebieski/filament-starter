<?php

declare(strict_types=1);

namespace App\Observers\User;

use App\Models\User\User;
use App\Models\Permission\Permission;

class UserObserver
{
    public function pivotDetached(User $user, string $relationName): void
    {
        if ($relationName === $user->tenants()->getRelationName()) {
            $user->revokePermissionTo(
                $user->tenantPermissions
                    ->map(fn (Permission $permission): string => $permission->name)
                    ->toArray()
            );
        }
    }

    public function deleted(User $user): void
    {
        $user->breezySessions()->delete();

        $user->notifications()->delete();

        $user->tokens()->delete();

        $user->ownedTenants()->delete();

        $user->permissions()->detach();

        $user->roles()->detach();
    }
}
