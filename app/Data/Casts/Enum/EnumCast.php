<?php

declare(strict_types=1);

namespace App\Data\Casts\Enum;

use App\Support\Enum\EnumInterface;
use App\Support\Enum\FromBoolInterface;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class EnumCast implements Cast
{
    public function __construct(private readonly string $enumName) {}

    /**
     * @param  EnumInterface|string|bool|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        /** @var array */
        $interfaces = class_implements($this->enumName);

        if (is_bool($value) && in_array(FromBoolInterface::class, $interfaces)) {
            return $this->enumName::fromBool($value);
        }

        if (is_string($value)) {
            return $this->enumName::from($value);
        }

        return $value;
    }
}
