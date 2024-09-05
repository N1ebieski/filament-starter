<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Handler;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetByFilterHandler extends Handler
{
    public function handle(GetByFilterQuery $query): LengthAwarePaginator|Collection|Builder
    {
        /** @var LengthAwarePaginator|Collection */
        $roles = $query->role->newQuery()
            ->selectRaw("`{$query->role->getTable()}`.*")
            ->filterSearchBy(
                searchBy: $query->searchBy,
                isOrderBy: is_null($query->orderBy)
            )
            ->filterExcept($query->except)
            ->withAllRelations()
            ->filterOrderBy($query->orderBy)
            ->filterResult($query->result);

        return $roles;
    }
}
