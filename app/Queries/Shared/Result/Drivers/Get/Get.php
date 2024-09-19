<?php

declare(strict_types=1);

namespace App\Queries\Shared\Result\Drivers\Get;

use App\Queries\Shared\Result\ResultInterface;
use App\Support\Attributes\Handler\Handler;

#[Handler(GetHandler::class)]
final class Get implements ResultInterface
{
    public function __construct(public readonly ?int $take = null) {}
}
