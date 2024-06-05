<?php

declare(strict_types=1);

namespace App\CacheQueries;

abstract class CacheQuery
{
    abstract public function getKey(): string;

    protected function hash(string|array $key): string
    {
        /** @var string */
        $json = json_encode($key);

        return md5($json);
    }
}
