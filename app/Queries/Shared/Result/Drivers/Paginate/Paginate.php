<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Paginate;

use App\Queries\Shared\Result\ResultInterface;
use App\Support\Attributes\Handler\Handler;

#[Handler(PaginateHandler::class)]
final class Paginate implements ResultInterface
{
    public function __construct(
        public int $perPage,
        public ?int $page = null
    ) {}
}
