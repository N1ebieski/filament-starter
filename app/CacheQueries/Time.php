<?php

declare(strict_types=1);

namespace App\CacheQueries;

final class Time
{
    public readonly int $staleMinutes;

    public function __construct(
        public readonly int $freshMinutes,
        ?int $staleMinutes = null
    ) {
        $this->staleMinutes = $staleMinutes ?? ($freshMinutes * 2);
    }
}
