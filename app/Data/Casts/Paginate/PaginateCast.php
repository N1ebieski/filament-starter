<?php

declare(strict_types=1);

namespace App\Data\Casts\Paginate;

use App\Queries\Paginate;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class PaginateCast implements Cast
{
    /**
     * @param int|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return is_int($value) ? new Paginate($value) : $value;
    }
}
