<?php

declare(strict_types=1);

namespace App\Queries;

use App\Support\Handler\HandlerHelper;
use Illuminate\Container\Container;

final class QueryBus implements QueryBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper
    ) {}

    public function execute(Query $query): mixed
    {
        $handler = $this->resolveHandler($query);

        return $handler->handle($query);
    }

    private function resolveHandler(Query $query): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($query));
    }
}
