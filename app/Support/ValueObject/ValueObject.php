<?php

declare(strict_types=1);

namespace App\Support\ValueObject;

use App\Casts\Cast;
use App\Casts\ValueObject\ValueObjectCast;
use App\Data\Data\Data;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Stringable;

/**
 * @property-read mixed $value
 */
abstract class ValueObject extends Data implements Castable, Stringable
{
    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function isEquals(self $value): bool
    {
        return $this->value === $value->value;
    }

    public static function castUsing(array $arguments): Cast
    {
        return new ValueObjectCast(static::class, ...$arguments);
    }
}
