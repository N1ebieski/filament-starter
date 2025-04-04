<?php

declare(strict_types=1);

namespace App\Observers\Role;

use App\Models\Role\Role;
use App\Observers\Observer;
use Illuminate\Database\ConnectionInterface as DB;

class RoleObserver extends Observer
{
    public function __construct(private readonly DB $db) {}

    public function deleting(Role $role): void
    {
        $this->db->transaction(function () use ($role): void {
            $role->permissions()->detach();

            $role->users()->detach();
        });
    }
}
