<?php

declare(strict_types=1);

namespace App\QueryBuilders\Role;

use App\QueryBuilders\Shared\Filters\HasFilters;
use Illuminate\Database\Eloquent\Builder;

final class RoleQueryBuilder extends Builder
{
    use HasFilters;
}
