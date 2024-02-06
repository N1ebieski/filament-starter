<?php

declare(strict_types=1);

namespace App\Commands\User\Tenants\Attach;

use App\Commands\Handler;
use App\Models\User\User;
use App\Models\Permission\Permission;

final class AttachHandler extends Handler
{
    public function handle(AttachCommand $command): User
    {
        $this->db->beginTransaction();

        try {
            $user = $command->user;

            $user->tenants()->attach($command->tenant);

            $user->givePermissionTo(
                $command->permissions->map(function (Permission $permission) {
                    return $permission->name;
                })->toArray()
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
