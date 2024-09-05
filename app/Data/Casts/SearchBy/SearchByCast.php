<?php

declare(strict_types=1);

namespace App\Data\Casts\SearchBy;

use App\Queries\SearchBy\SearchBy;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class SearchByCast implements Cast
{
    /**
     * @param SearchBy|string|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            if (mb_strlen($value) > 2) {
                return new SearchBy(term: $value, isOrderBy: true);
            }

            return null;
        }

        return $value;
    }
}
