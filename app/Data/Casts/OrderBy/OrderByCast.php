<?php

declare(strict_types=1);

namespace App\Data\Casts\OrderBy;

use App\Queries\Order;
use App\Queries\OrderBy;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Creation\CreationContext;

class OrderByCast implements Cast
{
    /**
     * @param OrderBy|string|null $value
     */
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        if (is_string($value)) {
            [$attribute, $order] = explode('|', $value);

            return new OrderBy(
                attribute: $attribute,
                order: Order::from($order)
            );
        }

        return $value;
    }
}
