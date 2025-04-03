<?php

declare(strict_types=1);

namespace App\Data\Casts\Enum;

use App\Data\Casts\Cast as BaseCast;
use App\Support\Enum\EnumInterface;
use App\Support\Enum\FromBoolInterface;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class EnumCast extends BaseCast implements Cast
{
    public function __construct(private readonly ?string $enumName = null) {}

    /**
     * @param  EnumInterface|string|bool|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        /** @var \Spatie\LaravelData\Support\Types\NamedType */
        $type = $property->type->type;

        $enumName = $this->enumName ?? $type->name;

        $interfaces = class_implements($enumName) ?: [];

        if (is_bool($value) && in_array(FromBoolInterface::class, $interfaces)) {
            return $enumName::fromBool($value);
        }

        if (is_string($value)) {
            return $enumName::from($value);
        }

        return $value;
    }
}
