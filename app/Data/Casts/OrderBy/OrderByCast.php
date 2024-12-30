<?php

declare(strict_types=1);

namespace App\Data\Casts\OrderBy;

use App\Data\Casts\Cast as BaseCast;
use App\Queries\Shared\OrderBy\Order;
use App\Queries\Shared\OrderBy\OrderBy;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class OrderByCast extends BaseCast implements Cast
{
    /**
     * @param  OrderBy|string|false|null  $value
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
