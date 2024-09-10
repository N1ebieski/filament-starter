<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\Scout;

use App\Queries\Shared\SearchBy\Drivers\Handler;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class ScoutHandler extends Handler
{
    public function handle(Scout $scout): Builder
    {
        return $this->builder->filterSearchByScout($scout);
    }
}
