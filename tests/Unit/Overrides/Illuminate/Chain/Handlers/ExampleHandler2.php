<?php

declare(strict_types=1);

namespace Tests\Unit\Overrides\Illuminate\Chain\Handlers;

final class ExampleHandler2 implements ExampleHandlerInterface
{
    public function handle(string $string, \Closure $next): void
    {
        if ($string === 'Test2') {
            //DO SOMETHING

            return;
        }

        $next($string);
    }
}
