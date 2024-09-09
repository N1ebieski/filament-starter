<?php

declare(strict_types=1);

namespace App\Queries\Result;

use App\Scopes\HasFilterableScopes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class Paginate implements ResultInterface
{
    public function __construct(
        public readonly int $perPage,
        public readonly ?int $page = null
    ) {
    }

    /**
     * @param Builder|HasFilterableScopes $builder
     */
    public function getResultBuilder(Builder $builder): LengthAwarePaginator
    {
        return $builder->filterPaginate($this);
    }
}
