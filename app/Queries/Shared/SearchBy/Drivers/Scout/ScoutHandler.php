<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\Scout;

use App\Scopes\SearchScopesInterface;
use App\Queries\Shared\SearchBy\Drivers\Handler;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @property-read Builder&SearchScopesInterface $builder
 */
final class ScoutHandler extends Handler
{
    public function __construct(private readonly Builder $builder)
    {
    }

    public function handle(Scout $scout): Builder
    {
        return $this->builder->filterSearchByScout($scout);
    }
}
