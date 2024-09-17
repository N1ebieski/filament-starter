<?php

declare(strict_types=1);

namespace App\CacheQueries;

use App\Data\Data\Data;
use App\Support\Hash\HasHash;

/**
 * @property-read Time|null $time
 */
abstract class CacheQuery extends Data
{
    use HasHash;

    abstract public function getKey(): string;
}
