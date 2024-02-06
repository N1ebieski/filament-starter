<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForAdmin;

use App\Queries\Query;
use App\Models\Role\Role;
use App\Models\Permission\Permission;

final class GetAvailableForAdminQuery extends Query
{
    public function __construct(
        public readonly Permission $permission = new Permission(),
        public readonly Role $role = new Role(),
    ) {
    }
}
