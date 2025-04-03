<?php

declare(strict_types=1);

namespace App\Observers\Role;

use App\Models\Role\Role;
use App\Observers\Observer;

readonly class RoleObserver extends Observer
{
    public function deleting(Role $role): void
    {
        $role->permissions()->detach();

        $role->users()->detach();
    }
}
