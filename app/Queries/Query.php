<?php

declare(strict_types=1);

namespace App\Queries;

abstract class Query
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
