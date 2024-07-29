<?php

declare(strict_types=1);

namespace App\Data\Data\Payload\EnsureIsFlatArray;

use Closure;
use App\Data\Data\Payload\Payload;
use App\Data\Data\Payload\HandlerInterface;

final class EnsureIsFlatArrayHandler implements HandlerInterface
{
    public function handle(Payload $payload, Closure $next): Payload
    {
        $payload->payloads = isset($payload->payloads[0]) ? $payload->payloads[0] : $payload->payloads;

        return $next($payload);
    }
}
