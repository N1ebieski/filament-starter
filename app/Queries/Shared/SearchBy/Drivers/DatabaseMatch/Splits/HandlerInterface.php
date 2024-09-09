<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\Splits;

use Closure;

interface HandlerInterface
{
    public function handle(Splits $splits, Closure $next): Splits;
}
