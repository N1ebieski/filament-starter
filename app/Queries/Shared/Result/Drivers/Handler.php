<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers;

use Illuminate\Contracts\Database\Eloquent\Builder;

abstract class Handler
{
    public function __construct(protected readonly Builder $builder)
    {
    }
}
