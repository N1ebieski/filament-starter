<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Get;
use App\Queries\Query;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Queries\Paginate;
use App\Filters\Role\RoleFilter;

final class GetByFilterQuery extends Query
{
    public function __construct(
        public readonly Role $role = new Role(),
        public readonly RoleFilter $filters = new RoleFilter(),
        public readonly ?OrderBy $orderby = null,
        public readonly Paginate|Get|null $result = null
    ) {
    }
}
