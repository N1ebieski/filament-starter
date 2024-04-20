<?php

declare(strict_types=1);

namespace Tests\Unit\Composite\Handlers;

final class ExampleHandler2 implements ExampleHandlerInterface
{
    public function handle(string $string): void
    {
        //DO SOMETHING
    }
}
