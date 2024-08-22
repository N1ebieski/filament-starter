<?php

declare(strict_types=1);

namespace App\CacheQueries\Shared;

use App\Data\Data\Data;
use App\Support\Hash\HasHash;

abstract class CacheQuery extends Data
{
    use HasHash;

    abstract public function getKey(): string;
}
