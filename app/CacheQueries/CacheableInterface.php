<?php

declare(strict_types=1);

namespace App\CacheQueries;

interface CacheableInterface
{
    public function isCache(): bool;
}
