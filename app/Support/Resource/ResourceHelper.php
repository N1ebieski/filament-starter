<?php

declare(strict_types=1);

namespace App\Support\Resource;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class ResourceHelper
{
    /**
     * @return class-string
     */
    public static function getResourceName(Model $model): string
    {
        /** @var class-string */
        $class = $model::class;

        $resourceName = Str::replace('Models', 'Http\\Resources', $class);

        return $resourceName.'Resource';
    }
}
