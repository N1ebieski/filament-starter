<?php

declare(strict_types=1);

namespace App\Queries;

use Illuminate\Container\Container;
use App\Support\Handler\HandlerHelper;

final class QueryBus implements QueryBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper
    ) {
    }

    public function execute(Query $query): mixed
    {
        $handler = $this->resolveHandler($query);

        //@phpstan-ignore-next-line
        /** @disregard */
        return $handler->handle($query);
    }

    private function resolveHandler(Query $query): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($query));
    }
}
