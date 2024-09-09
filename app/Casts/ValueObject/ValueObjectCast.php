<?php

declare(strict_types=1);

namespace App\Casts\ValueObject;

use App\Casts\Cast;
use App\ValueObjects\ValueObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class ValueObjectCast extends Cast implements CastsAttributes
{
    public function __construct(
        private readonly string $valueObjectName,
        private readonly bool $nullable = false
    ) {
    }

    /**
     * Transform the attribute from the underlying model values.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ValueObject
    {
        if ($this->nullable && is_null($value)) {
            return $value;
        }

        $valueObject = $value;

        if (!$value instanceof $this->valueObjectName) {
            /** @var ValueObject */
            $valueObject = new ($this->valueObjectName)($value);
        }

        return $valueObject;
    }

    /**
     * Transform the attribute to its underlying model values.
     *
     * @param ValueObject|mixed $value
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($this->nullable && is_null($value)) {
            return $value;
        }

        $valueObject = $value;

        if (!$value instanceof $this->valueObjectName) {
            /** @var ValueObject */
            $valueObject = new ($this->valueObjectName)($value);
        }

        return $valueObject->value;
    }
}
