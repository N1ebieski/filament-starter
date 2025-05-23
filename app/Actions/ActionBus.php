<?php

declare(strict_types=1);

namespace App\Actions;

use App\Support\Handler\HandlerHelper;
use Illuminate\Container\Container;

final class ActionBus implements ActionBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper
    ) {}

    public function execute(Action $action): mixed
    {
        $handler = $this->resolveHandler($action);

        return $handler->handle($action);
    }

    private function resolveHandler(Action $action): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($action));
    }
}
