<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForTenant;

use App\Queries\Query;
use App\Models\Permission\Permission;

final class GetAvailableForTenantQuery extends Query
{
    public function __construct(
        public readonly Permission $permission = new Permission()
    ) {
    }
}
