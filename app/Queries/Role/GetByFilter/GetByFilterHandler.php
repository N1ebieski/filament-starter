<?php

declare(strict_types=1);

namespace App\Queries\Role\GetByFilter;

use App\Queries\Handler;
use App\Models\Role\Role;
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
            ->when(!is_null($query->filters->search), function (Builder|Role $builder) use ($query) {
                return $builder->filterSearch($query->filters->search)
                    ->filterSearchAttributes($query->filters->search);
            })
            ->filterExcept($query->filters->except)
            ->when(is_null($query->orderby), function (Builder|Role $builder) use ($query) {
                return $builder->filterOrderBySearch($query->filters->search);
            }, function (Builder|Role $builder) use ($query) {
                return $builder->filterOrderBy($query->orderby);
            })
            ->withAllRelations()
            ->filterResult($query->result);

        return $roles;
    }
}
