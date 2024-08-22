<?php

declare(strict_types=1);

namespace App\CacheQueries\Shared;

interface CacheableInterface
{
    public function isCache(): bool;
}
