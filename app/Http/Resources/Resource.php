<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Data\Data\Data;

abstract class Resource extends Data
{
    public static function getAllowedSelect(): array
    {
        return [];
    }

    public static function getAllowedWith(): array
    {
        return [];
    }
}
