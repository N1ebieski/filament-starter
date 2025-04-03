<?php

declare(strict_types=1);

namespace App\CacheQueries;

final class Time
{
    public function __construct(public readonly int $minutes) {}
}
