<?php

declare(strict_types=1);

namespace App\Queries\SearchBy\Drivers\Scout;

use Closure;
use App\Data\Data\Data;
use App\Queries\SearchBy\SearchByInterface;

final class Scout extends Data implements SearchByInterface
{
    public function __construct(
        public readonly string $query,
        public readonly Closure $callback
    ) {
    }
}
