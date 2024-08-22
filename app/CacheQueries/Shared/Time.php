<?php

declare(strict_types=1);

namespace App\CacheQueries\Shared;

final class Time
{
    public function __construct(public readonly int $minutes)
    {
    }
}
