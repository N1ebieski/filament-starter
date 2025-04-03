<?php

declare(strict_types=1);

namespace App\Queries;

use App\Support\Handler\HandlerHelper;
use Illuminate\Container\Container;

final readonly class QueryBus implements QueryBusInterface
{
    public function __construct(
        private Container $container,
        private HandlerHelper $handlerHelper
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
