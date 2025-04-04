<?php

declare(strict_types=1);

namespace App\Observers\Permission;

use App\Models\Permission\Permission;
use App\Observers\Observer;
use Illuminate\Database\ConnectionInterface as DB;

class PermissionObserver extends Observer
{
    public function __construct(private readonly DB $db) {}

    public function deleting(Permission $permission): void
    {
        $this->db->transaction(function () use ($permission) {
            $permission->users()->detach();
        });
    }
}
