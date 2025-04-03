<?php

declare(strict_types=1);

namespace App\Queries\Shared\OrderBy;

final readonly class OrderBy
{
    public function __construct(
        public string $attribute,
        public Order $order
    ) {}
}
