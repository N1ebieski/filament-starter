<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Paginate;

use App\Scopes\HasFilterableScopes;
use App\Queries\Shared\SearchBy\Drivers\Handler;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @property-read Builder&HasFilterableScopes $builder
 */
final class PaginateHandler extends Handler
{
    public function handle(Paginate $paginate): LengthAwarePaginator
    {
        return $this->builder->filterPaginate($paginate);
    }
}
