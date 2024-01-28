<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailable;

use App\Queries\Query;
use App\Models\Role\Role;
use App\Models\Permission\Permission;

final class GetAvailableQuery extends Query
{
    public function __construct(
        public readonly Permission $permission = new Permission(),
        public readonly Role $role = new Role(),
    ) {
    }
}
