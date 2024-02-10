<?php

declare(strict_types=1);

namespace App\Observers\Role;

use App\Models\Role\Role;

class RoleObserver
{
    public function deleting(Role $role): void
    {
        $role->permissions()->detach();

        $role->users()->detach();
    }
}
