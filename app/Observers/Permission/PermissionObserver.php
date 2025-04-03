<?php

declare(strict_types=1);

namespace App\Observers\Permission;

use App\Models\Permission\Permission;
use App\Observers\Observer;

readonly class PermissionObserver extends Observer
{
    public function deleting(Permission $permission): void
    {
        $permission->users()->detach();
    }
}
