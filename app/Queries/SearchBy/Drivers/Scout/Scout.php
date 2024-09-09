<?php

declare(strict_types=1);

namespace App\Queries\SearchBy\Drivers\Scout;

use Closure;
use App\Data\Data\Data;
use App\Queries\Result\Get;
use App\Scopes\HasSearchScopes;
use App\Queries\SearchBy\SearchByInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class Scout extends Data implements SearchByInterface
{
    public function __construct(
        public readonly string $query,
        public readonly ?Closure $callback = null,
        public readonly Get $get = new Get(take: 1000)
    ) {
    }

    /**
     * @param Builder|HasSearchScopes $builder
     */
    public function getSearchBuilder(Builder $builder): Builder
    {
        return $builder->filterSearchByScout($this);
    }
}
