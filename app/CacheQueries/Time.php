<?php

declare(strict_types=1);

namespace App\CacheQueries;

final readonly class Time
{
    public function __construct(public int $minutes) {}
}
