<?php

declare(strict_types=1);

namespace Tests\Unit\Overrides\Illuminate\Pipeline\Handlers;

interface ExampleHandlerInterface
{
    public function handle(string $string, \Closure $next): string;
}
