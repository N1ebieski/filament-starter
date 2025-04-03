<?php

declare(strict_types=1);

namespace App\Data\Casts\Time;

use App\CacheQueries\Time;
use App\Data\Casts\Cast as BaseCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class TimeCast extends BaseCast implements Cast
{
    /**
     * @param  Time|int|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return is_int($value) ? new Time($value) : $value;
    }
}
