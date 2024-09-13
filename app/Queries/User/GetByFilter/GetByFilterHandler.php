<?php

declare(strict_types=1);

namespace App\Queries\User\GetByFilter;

use App\Queries\Handler;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetByFilterHandler extends Handler
{
    public function handle(GetByFilterQuery $query): LengthAwarePaginator|Collection|Builder
    {
        /** @var LengthAwarePaginator|Collection|Builder */
        $users = $query->user->newQuery()
            ->filterSelect($query->select)
            ->filterSearchBy($query->searchBy)
            ->filterStatusEmail($query->status_email)
            ->filterIgnore($query->ignore)
            ->filterRoles($query->roles)
            ->filterTenants($query->tenants)
            ->filterWith($query->with)
            ->filterOrderBy($query->orderBy)
            ->filterResult($query->result);

        return $users;
    }
}
