<?php

declare(strict_types=1);

namespace Tests\Unit\Composite\Handlers;

interface ExampleHandlerInterface
{
    public function handle(string $string): void;
}
