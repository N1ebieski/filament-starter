<?php

declare(strict_types=1);

namespace App\CacheQueries;

use Illuminate\Container\Container;
use App\Support\Handler\HandlerHelper;

final class CacheQueryBus implements CacheQueryBusInterface
{
    private Container $container;

    private HandlerHelper $handlerHelper;

    public function __construct(
        Container $container,
        HandlerHelper $handlerHelper
    ) {
        $this->container = $container;
        $this->handlerHelper = $handlerHelper;
    }

    public function execute(CacheQuery $query): mixed
    {
        $handler = $this->resolveHandler($query);

        //@phpstan-ignore-next-line
        /** @disregard */
        return $handler->handle($query);
    }

    private function resolveHandler(CacheQuery $query): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($query));
    }
}
