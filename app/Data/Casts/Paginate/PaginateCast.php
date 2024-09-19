<?php

declare(strict_types=1);

namespace App\Data\Casts\Paginate;

use App\Queries\Shared\Result\Drivers\Paginate\Paginate;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class PaginateCast implements Cast
{
    /**
     * @param  Paginate|int|null  $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        return is_int($value) ? new Paginate($value) : $value;
    }
}
