<?php

declare(strict_types=1);

namespace App\Data\Casts\Time;

use App\CacheQueries\Time;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class TimeCast implements Cast
{
    /**
     * @param Time|int|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return is_int($value) ? new Time($value) : $value;
    }
}
