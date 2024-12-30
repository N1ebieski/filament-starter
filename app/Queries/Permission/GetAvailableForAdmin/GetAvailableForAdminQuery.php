<?php

declare(strict_types=1);

namespace App\Queries\Permission\GetAvailableForAdmin;

use App\Data\Pipelines\ObjectDefaultsDataPipe\ObjectDefaultsInterface;
use App\Models\Permission\Permission;
use App\Models\Role\Role;
use App\Queries\Query;
use App\Support\Attributes\Handler\Handler;

#[Handler(\App\Queries\Permission\GetAvailableForAdmin\GetAvailableForAdminHandler::class)]
final class GetAvailableForAdminQuery extends Query implements ObjectDefaultsInterface
{
    public function __construct(
        public readonly Permission $permission = new Permission,
        public readonly Role $role = new Role,
    ) {}
}
