<?php

declare(strict_types=1);

namespace App\ValueObjects;

use AllowDynamicProperties;
use App\Data\Data\Data;
use Stringable;

/**
 * @property-read mixed $value
 */
#[AllowDynamicProperties]
abstract class ValueObject extends Data implements Stringable
{
    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function isEquals(self $value): bool
    {
        return $this->value === $value->value;
    }
}
