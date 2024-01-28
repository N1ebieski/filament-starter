<?php

declare(strict_types=1);

namespace App\Filters\Role;

use App\Filters\Filter;
use App\Queries\Search;

final class RoleFilter extends Filter
{
    public function __construct(
        public readonly ?Search $search = null,
        public readonly ?array $except = null,
    ) {
    }
}
