<?php

declare(strict_types=1);

namespace App\Support\Query\Include;

use Illuminate\Support\Str;

final class IncludeHelper
{
    public static function getScopeRelationName(string $name): string
    {
        $scopeName = 'scopeWith'.Str::of($name)
            ->beforeLast(':')
            ->camel()
            ->ucfirst();

        return $scopeName;
    }
}
