<?php

declare(strict_types=1);

namespace App\Commands\Role\Edit;

use App\Commands\Handler;
use App\Models\Role\Role;
use App\Models\Permission\Permission;
use App\Commands\Role\Edit\EditCommand;

final class EditHandler extends Handler
{
    public function handle(EditCommand $command): Role
    {
        $this->db->beginTransaction();

        try {
            $role = $command->role->fill(
                $command->only($command->role->getFillable())
            );

            $role->save();

            $role->syncPermissions(
                $command->permissions->map(function (Permission $permission) {
                    return $permission->name;
                })->toArray()
            );
        } catch (\Exception $e) {
            $this->db->rollBack();

            throw $e;
        }

        $this->db->commit();

        /** @var Role */
        return $role->fresh();
    }
}
