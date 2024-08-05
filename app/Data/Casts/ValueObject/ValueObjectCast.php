<?php

declare(strict_types=1);

namespace App\Data\Casts\ValueObject;

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class ValueObjectCast implements Cast
{
    public function __construct(private readonly string $valueObjectName)
    {
    }

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (!$value instanceof $this->valueObjectName) {
            $value = new ($this->valueObjectName)($value);
        }

        return $value;
    }
}
