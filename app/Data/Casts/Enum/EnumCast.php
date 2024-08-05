<?php

declare(strict_types=1);

namespace App\Data\Casts\Enum;

use Spatie\LaravelData\Casts\Cast;
use App\Support\Enum\FromBoolInterface;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class EnumCast implements Cast
{
    public function __construct(private readonly string $enumName)
    {
    }

    /**
     * @param string|bool|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        /** @var array */
        $interfaces = class_implements($this->enumName);

        if (is_bool($value) && in_array(FromBoolInterface::class, $interfaces)) {
            return $this->enumName::fromBool($value);
        }

        return $this->enumName::tryFrom($value);
    }
}
