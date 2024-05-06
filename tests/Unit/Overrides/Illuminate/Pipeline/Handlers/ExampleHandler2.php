<?php

declare(strict_types=1);

namespace Tests\Unit\Overrides\Illuminate\Pipeline\Handlers;

use Override;

final class ExampleHandler2 implements ExampleHandlerInterface
{
    #[Override]
    public function handle(string $string, \Closure $next): string
    {
        $string .= '2';

        return $next($string);
    }
}
