<?php

declare(strict_types=1);

namespace App\CacheQueries;

use App\Support\Handler\HandlerHelper;
use Illuminate\Container\Container;

final class CacheQueryBus implements CacheQueryBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper
    ) {}

    public function execute(CacheQuery $query): mixed
    {
        $handler = $this->resolveHandler($query);

        return $handler->handle($query);
    }

    private function resolveHandler(CacheQuery $query): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($query));
    }
}
