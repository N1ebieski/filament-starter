<?php

declare(strict_types=1);

namespace App\Commands\Role\Create;

use App\Commands\Handler;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use Illuminate\Database\ConnectionInterface as DB;

final readonly class CreateHandler extends Handler
{
    public function __construct(
        private DB $db,
    ) {}

    public function handle(CreateCommand $command): Role
    {
        $this->db->beginTransaction();

        try {
            $role = $command->role->newInstance(
                $command->only(...$command->role->getFillable())->toArray()
            );

            $role->save();

            $role->givePermissionTo(
                $command->permissions->map(
                    fn (Permission $permission) => $permission->name->value)->toArray()
            );
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        /** @var Role */
        return $role->fresh();
    }
}
