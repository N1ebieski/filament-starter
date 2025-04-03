<?php

declare(strict_types=1);

namespace App\Data\Casts\Select;

use App\Data\Casts\Cast as BaseCast;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final readonly class SelectCast extends BaseCast implements Cast
{
    /**
     * @param  array|string|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            return explode(', ', $value);
        }

        return $value;
    }
}
