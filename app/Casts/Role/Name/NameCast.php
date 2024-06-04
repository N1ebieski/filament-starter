<?php

declare(strict_types=1);

namespace App\Casts\Role\Name;

use App\ValueObjects\Role\Name\Name;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class NameCast implements CastsAttributes
{
    /**
     * Transform the attribute from the underlying model values.
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Name
    {
        return (!$value instanceof Name) ? new Name($value) : $value;
    }

    /**
     * Transform the attribute to its underlying model values.
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (is_string($value)) {
            $value = new Name($value);
        }

        if (!$value instanceof Name) {
            throw new \InvalidArgumentException('The given value is not a Name instance');
        }

        return $value->value;
    }
}
