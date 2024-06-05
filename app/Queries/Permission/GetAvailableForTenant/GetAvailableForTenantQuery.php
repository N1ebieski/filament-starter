<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForTenant;

use App\Queries\Query;
use App\Support\Attributes\Handler\Handler;
use App\Models\Permission\Permission;

#[Handler(\App\Queries\Permission\GetAvailableForTenant\GetAvailableForTenantHandler::class)]
final class GetAvailableForTenantQuery extends Query
{
    public function __construct(
        public readonly Permission $permission = new Permission()
    ) {
    }
}
