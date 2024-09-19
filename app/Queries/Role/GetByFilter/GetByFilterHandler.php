<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Handler;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class GetByFilterHandler extends Handler
{
    public function handle(GetByFilterQuery $query): LengthAwarePaginator|Collection|Builder
    {
        /** @var LengthAwarePaginator|Collection */
        $roles = $query->role->newQuery()
            ->filterSelect($query->select)
            ->filterSearchBy($query->searchBy)
            ->filterIgnore($query->ignore)
            ->filterWith($query->with)
            ->filterOrderBy($query->orderBy)
            ->filterResult($query->result);

        return $roles;
    }
}
