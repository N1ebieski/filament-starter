<?php

declare(strict_types=1);

namespace App\Data\Casts\Include;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class IncludeCast implements Cast
{
    /**
     * @param array<int|string, string|array>|string|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            $valueAsArray = explode(', ', $value);

            return $valueAsArray;
        }

        if (is_array($value)) {
            $valueWithKeys = Collection::make($value)
                ->mapWithKeys(function (string|array $selects, int|string $relation): array {
                    if (is_int($relation) && is_string($selects)) {
                        return [$selects => ['*']];
                    }

                    $selectsAsArray = is_string($selects) ? explode(', ', $selects) : $selects;

                    return [$relation => $selectsAsArray];
                })
                ->toArray();

            return $valueWithKeys;
        }

        return $value;
    }
}
