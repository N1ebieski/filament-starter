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
        /** @var Role $role */
        $role = $this->db->transaction(function () use ($command) {
            $role = $command->role->fill(
                $command->only(...$command->role->getFillable())->toArray()
            );

            $role->save();

            if (! ($command->permissions instanceof Optional)) {
                $role->syncPermissions(
                    $command->permissions->map(
                        fn (Permission $permission) => $permission->name->value)->toArray()
                );
            }

            return $role;
        });

        $role->refresh();

        return $role;
    }
}
