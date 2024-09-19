<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Paginate;

use App\Queries\Shared\Result\Drivers\Handler;
use App\Scopes\FiltersScopesInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * @property-read Builder&FiltersScopesInterface $builder
 */
final class PaginateHandler extends Handler
{
    public function __construct(private readonly Builder $builder) {}

    public function handle(Paginate $paginate): LengthAwarePaginator
    {
        return $this->builder->filterPaginate($paginate);
    }
}
