<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Stringable;
use AllowDynamicProperties;

/**
 * @property-read mixed $value
 */
#[AllowDynamicProperties]
abstract class ValueObject implements Stringable
{
    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function isEquals(self $value): bool
    {
        return $this->value === $value->value;
    }
}
