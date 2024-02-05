<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Queries\Handler;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetByFilterHandler extends Handler
{
    public function handle(GetByFilterQuery $query): LengthAwarePaginator|Collection|Builder
    {
        /** @var LengthAwarePaginator|Collection|Builder */
        $users = $query->user->newQuery()
            ->selectRaw("`{$query->user->getTable()}`.*")
            ->when(!is_null($query->filters->search), function (Builder|User $builder) use ($query) {
                return $builder->filterSearch($query->filters->search)
                    ->filterSearchAttributes($query->filters->search);
            })
            ->filterStatusEmail($query->filters->status_email)
            ->filterExcept($query->filters->except)
            ->filterRoles($query->filters->roles)
            ->filterTenants($query->filters->tenants)
            ->when(is_null($query->orderby), function (Builder|User $builder) use ($query) {
                return $builder->filterOrderBySearch($query->filters->search);
            }, function (Builder|User $builder) use ($query) {
                return $builder->filterOrderBy($query->orderby);
            })
            ->withAllRelations()
            ->filterResult($query->result);

        return $users;
    }
}
