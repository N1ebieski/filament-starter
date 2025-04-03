<?php

declare(strict_types=1);

namespace App\Support\Query\Include;

use Illuminate\Support\Str;

final class IncludeHelper
{
    public static function getBuilderRelationName(string $name): string
    {
        return 'with'.Str::of($name)
            ->beforeLast(':')
            ->camel()
            ->ucfirst();
    }
}
