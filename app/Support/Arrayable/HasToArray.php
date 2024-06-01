<?php

declare(strict_types=1);

namespace App\Support\Arrayable;

use Illuminate\Contracts\Support\Arrayable;

trait HasToArray
{
    public function toArray(): array
    {
        $attributes = get_object_vars($this);

        foreach ($attributes as $key => $value) {
            if ($value instanceof Arrayable) {
                $attributes[$key] = $value->toArray();
            }
        }

        return $attributes;
    }
}
