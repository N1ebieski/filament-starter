<?php

declare(strict_types=1);

namespace App\Commands;

abstract class Command
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function only(array $attributes): array
    {
        return array_intersect_key($this->toArray(), array_flip($attributes));
    }
}
