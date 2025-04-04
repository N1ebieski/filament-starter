<?php

declare(strict_types=1);

namespace App\Data\Casts\ValueObject;

use App\Data\Casts\Cast as BaseCast;
use App\Support\ValueObject\ValueObject;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class ValueObjectCast extends BaseCast implements Cast
{
    public function __construct(private readonly ?string $valueObjectName = null) {}

    /**
     * @param  ValueObject|string|int|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        /** @var \Spatie\LaravelData\Support\Types\NamedType */
        $type = $property->type->type;

        $valueObjectName = $this->valueObjectName ?? $type->name;

        if (! is_null($value) && ! $value instanceof $valueObjectName) {
            return new ($valueObjectName)($value);
        }

        return $value;
    }
}
