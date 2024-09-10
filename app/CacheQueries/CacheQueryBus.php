<?php

declare(strict_types=1);

namespace App\CacheQueries;

use Illuminate\Container\Container;
use App\Support\Handler\HandlerHelper;

final class CacheQueryBus implements CacheQueryBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper
    ) {
    }

    public function execute(CacheQuery $query): mixed
    {
        $handler = $this->resolveHandler($query);

        /** @disregard */
        //@phpstan-ignore-next-line
        return $handler->handle($query);
    }

    private function resolveHandler(CacheQuery $query): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($query));
    }
}
