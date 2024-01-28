<?php

declare(strict_types=1);

namespace App\Filters;

use Illuminate\Support\Collection;

abstract class Filter
{
    public function toCollect(): Collection
    {
        return new Collection(get_object_vars($this));
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
