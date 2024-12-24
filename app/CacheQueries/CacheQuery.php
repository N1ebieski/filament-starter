<?php

declare(strict_types=1);

namespace App\CacheQueries;

use App\Data\Data\Data;
use App\Support\Hash\HasHash;

abstract class CacheQuery extends Data
{
    use HasHash;

    abstract public ?Time $time { get; }

    abstract public function getKey(): string;
}
