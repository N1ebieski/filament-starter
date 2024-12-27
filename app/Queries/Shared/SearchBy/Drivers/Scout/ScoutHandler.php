<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\Scout;

use App\Queries\Shared\SearchBy\Drivers\Handler;
use App\QueryBuilders\Shared\Search\SearchInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&SearchInterface $builder
 */
final class ScoutHandler extends Handler
{
    public function __construct(private readonly Builder $builder) {}

    public function handle(Scout $scout): Builder
    {
        return $this->builder->filterSearchByScout($scout);
    }
}
