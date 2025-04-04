<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Handler;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use Illuminate\Database\ConnectionInterface as DB;

final class CreateHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
    ) {}

    public function handle(CreateCommand $command): Role
    {
        /** @var Role $role */
        $role = $this->db->transaction(function () use ($command): Role {
            $role = $command->role->newInstance(
                $command->only(...$command->role->getFillable())->toArray()
            );

            $role->save();

            $role->givePermissionTo(
                $command->permissions->map(
                    fn (Permission $permission) => $permission->name->value)->toArray()
            );

            return $role;
        });

        $role->refresh();

        return $role;
    }
}
