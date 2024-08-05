<?php

declare(strict_types=1);

namespace App\Support\Hash;

trait HasHash
{
    protected function hash(string|array $key): string
    {
        /** @var string */
        $json = json_encode($key);

        return md5($json);
    }
}
