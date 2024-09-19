<?php

declare(strict_types=1);

namespace App\Data\Casts\ValueObject;

use App\ValueObjects\ValueObject;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class ValueObjectCast implements Cast
{
    public function __construct(private readonly string $valueObjectName) {}

    /**
     * @param  ValueObject|string|int|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (! is_null($value) && ! $value instanceof $this->valueObjectName) {
            $value = new ($this->valueObjectName)($value);
        }

        return $value;
    }
}
