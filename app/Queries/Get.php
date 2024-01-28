<?php

declare(strict_types=1);

namespace App\Queries;

final class Get
{
    public function __construct(public readonly ?int $take = null)
    {
    }
}
