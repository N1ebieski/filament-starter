<?php

declare(strict_types=1);

namespace App\ValueObjects\Role\Name;

use App\ValueObjects\ValueObject;
use App\ValueObjects\Role\DefaultName\DefaultName;

final class Name extends ValueObject
{
    public function __construct(public readonly string $value)
    {
    }

    public function isAdmin(): bool
    {
        foreach ([DefaultName::SuperAdmin, DefaultName::Admin] as $name) {
            if ($this->isEquals(new self($name->value))) {
                return true;
            }
        }

        return false;
    }

    public function isDefault(): bool
    {
        foreach (DefaultName::cases() as $name) {
            if ($this->isEquals(new self($name->value))) {
                return true;
            }
        }

        return false;
    }

    public function isEqualsDefault(DefaultName $value): bool
    {
        return $this->value === $value->value;
    }
}
