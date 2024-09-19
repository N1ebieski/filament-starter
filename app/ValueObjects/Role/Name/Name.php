<?php

declare(strict_types=1);

namespace App\ValueObjects\Role\Name;

use App\ValueObjects\ValueObject;
use Spatie\LaravelData\Attributes\Validation\Max;

final class Name extends ValueObject
{
    public function __construct(
        #[Max(255)]
        public readonly string $value
    ) {
        $this->validate(['value' => $value]);
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
