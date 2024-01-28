<?php

declare(strict_types=1);

namespace App\Commands;

abstract class Command
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
