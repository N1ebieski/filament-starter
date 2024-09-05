<?php

declare(strict_types=1);

namespace App\Queries\Result;

final class Paginate implements ResultInterface
{
    public function __construct(
        public readonly int $perPage,
        public readonly ?int $page = null
    ) {
    }
}
