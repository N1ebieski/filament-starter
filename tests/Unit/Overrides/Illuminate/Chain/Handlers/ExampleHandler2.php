<?php

declare(strict_types=1);

namespace Tests\Unit\Overrides\Illuminate\Chain\Handlers;

use Override;

final class ExampleHandler2 implements ExampleHandlerInterface
{
    #[Override]
    public function handle(string $string, \Closure $next): void
    {
        if ($string === 'Test2') {
            // DO SOMETHING

            return;
        }

        $next($string);
    }
}
