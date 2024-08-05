<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForTenant;

use App\Queries\Query;
use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Permission\Permission;
use App\Support\Attributes\Handler\Handler;

#[Handler(\App\Queries\Permission\GetAvailableForTenant\GetAvailableForTenantHandler::class)]
final class GetAvailableForTenantQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Permission $permission = new Permission()
    ) {
    }
}
