<?php

declare(strict_types=1);

namespace App\Data\Casts\With;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class WithCast implements Cast
{
    /**
     * @param array<int, string>|string|null $value
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
