<?php

declare(strict_types=1);

namespace App\Support\Query\Includes;

use Illuminate\Support\Str;

final class IncludesHelper
{
    public static function getScopeRelationName(string $name): string
    {
        $scopeName = 'scopeWith' . Str::of($name)->camel()->ucfirst();

        return $scopeName;
    }
}
