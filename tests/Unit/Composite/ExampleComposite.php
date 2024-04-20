<?php

declare(strict_types=1);

namespace Tests\Unit\Composite;

use Tests\Unit\Composite\Handlers\ExampleHandlerInterface;

final class ExampleComposite
{
    /**
     * @var array<ExampleHandlerInterface>
     */
    private array $handlers = [];

    public function through(ExampleHandlerInterface ...$handlers): self
    {
        $this->handlers = $handlers;

        return $this;
    }

    public function process(string $string): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($string);
        }
    }
}
