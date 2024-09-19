<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\Scout;

use App\Data\Data\Data;
use App\Queries\Shared\Result\Drivers\Get\Get;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\Support\Attributes\Handler\Handler;
use Closure;

#[Handler(ScoutHandler::class)]
final class Scout extends Data implements SearchByInterface
{
    public function __construct(
        public readonly string $query,
        public readonly ?Closure $callback = null,
        public readonly Get $get = new Get(take: 1000)
    ) {}
}
