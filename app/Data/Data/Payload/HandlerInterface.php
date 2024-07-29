<?php

declare(strict_types=1);

namespace App\Data\Data\Payload;

use Closure;

interface HandlerInterface
{
    public function handle(Payload $payload, Closure $next): Payload;
}
