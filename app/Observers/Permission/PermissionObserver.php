<?php

declare(strict_types=1);

namespace App\Observers\Permission;

use App\Models\Permission\Permission;

class PermissionObserver
{
    public function deleted(Permission $permission): void
    {
        $permission->users()->detach();
    }
}
