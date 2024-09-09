<?php

declare(strict_types=1);

namespace App\Queries\SearchBy;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface SearchByInterface
{
    public function getSearchBuilder(Builder $builder): Builder;
}
