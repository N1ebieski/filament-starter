<?php

declare(strict_types=1);

namespace App\CacheQueries;

use App\Data\Data\Data;

abstract class CacheQuery extends Data
{
    abstract public function getKey(): string;

    protected function hash(string|array $key): string
    {
        /** @var string */
        $json = json_encode($key);

        return md5($json);
    }
}
