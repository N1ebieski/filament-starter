<?php

declare(strict_types=1);

namespace Tests\Unit\Composite\Handlers;

use Override;

final class ExampleHandler2 implements ExampleHandlerInterface
{
    #[Override]
    public function handle(string $string): void
    {
        // DO SOMETHING
    }
}
