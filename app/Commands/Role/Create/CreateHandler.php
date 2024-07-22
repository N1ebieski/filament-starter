<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Handler;
use App\Models\Role\Role;
use App\Models\Permission\Permission;
use App\Commands\Role\Create\CreateCommand;

final class CreateHandler extends Handler
{
    public function handle(CreateCommand $command): Role
    {
        $this->db->beginTransaction();

        try {
            $role = $command->role->newInstance(
                $command->only(...$command->role->getFillable())->toArray()
            );

            $role->save();

            $role->givePermissionTo(
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
