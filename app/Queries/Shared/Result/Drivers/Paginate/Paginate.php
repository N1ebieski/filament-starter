<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Paginate;

use App\Support\Attributes\Handler\Handler;
use App\Queries\Shared\Result\ResultInterface;

#[Handler(PaginateHandler::class)]
final class Paginate implements ResultInterface
{
    public function __construct(
        public readonly ?int $perPage = null,
        public readonly ?int $page = null
    ) {
    }
}
