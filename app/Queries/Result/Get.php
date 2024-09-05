<?php

declare(strict_types=1);

namespace App\Queries\Result;

final class Get implements ResultInterface
{
    public function __construct(public readonly ?int $take = null)
    {
    }
}
