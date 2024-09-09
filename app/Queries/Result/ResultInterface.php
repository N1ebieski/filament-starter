<?php

declare(strict_types=1);

namespace App\Queries\Result;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Database\Eloquent\Builder;

interface ResultInterface
{
    public function getResultBuilder(Builder $builder): Paginator|Collection;
}
