<?php

declare(strict_types=1);

namespace App\Data\Casts\Select;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class SelectCast implements Cast
{
    /**
     * @param array|string|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            $valueAsArray = explode(', ', $value);

            return $valueAsArray;
        }

        return $value;
    }
}
