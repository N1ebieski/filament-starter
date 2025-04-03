<?php

declare(strict_types=1);

namespace App\Commands\Role\Edit;

use App\Commands\Handler;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use Illuminate\Database\ConnectionInterface as DB;
use Spatie\LaravelData\Optional;

final class EditHandler extends Handler
{
    public function __construct(
        private readonly DB $db,
    ) {}

    public function handle(EditCommand $command): Role
    {
        $this->db->beginTransaction();

        try {
            $role = $command->role->fill(
                $command->only(...$command->role->getFillable())->toArray()
            );

            $role->save();

            if (! ($command->permissions instanceof Optional)) {
                $role->syncPermissions(
                    $command->permissions->map(function (Permission $permission) {
                        return $permission->name->value;
                    })->toArray()
                );
            }
        } catch (\Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        }

        $this->db->commit();

        /** @var Role */
        return $role->fresh();
    }
}
