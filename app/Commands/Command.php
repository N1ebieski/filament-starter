<?php

declare(strict_types=1);

namespace App\Commands;

use App\Support\Arrayable\HasToArray;
use Illuminate\Contracts\Support\Arrayable;

abstract class Command implements Arrayable
{
    use HasToArray;

    public function only(array $attributes): array
    {
        return array_intersect_key($this->toArray(), array_flip($attributes));
    }
}
