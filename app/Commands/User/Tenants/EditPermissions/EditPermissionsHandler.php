<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\EditPermissions;

use App\Commands\Handler;
use App\Models\User\User;
use App\Models\Permission\Permission;

final class EditPermissionsHandler extends Handler
{
    public function handle(EditPermissionsCommand $command): User
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user;

            $user->revokeTenantPermissionTo(
                $command->user->tenantPermissions
                    ->map(fn (Permission $permission): string => $permission->name)
                    ->toArray()
            );

            $user->givePermissionTo(
                $command->permissions
                    ->map(fn (Permission $permission): string => $permission->name)
                    ->toArray()
            );
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        /** @var User */
        return $user->fresh();
    }
}
