<?php

declare(strict_types=1);

namespace App\Support\Resource;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

final class ResourceHelper
{
    public static function getResourceName(Model $model): string
    {
        /** @var string */
        $class = get_class($model);

        /** @var string */
        $resourceName = Str::replace('Models', 'Http\\Resources', $class);

        $resourceName .= 'Resource';

        return $resourceName;
    }
}
