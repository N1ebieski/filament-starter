<?php

declare(strict_types=1);

namespace App\QueryBuilders\Role;

use App\QueryBuilders\Shared\Filters\HasFilters;
use App\QueryBuilders\Shared\Search\SearchInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel of \App\Models\Role\Role
 */
final class RoleQueryBuilder extends Builder implements SearchInterface
{
    use HasFilters;
}
