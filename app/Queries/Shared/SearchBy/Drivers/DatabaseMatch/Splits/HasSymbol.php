<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits;

use Illuminate\Support\Str;

trait HasSymbol
{
    private function isContainsSymbol(string $match): bool
    {
        return Str::contains($match, ['.', '-', '+', '<', '>', '@', '*', '(', ')', '~']);
    }
}
