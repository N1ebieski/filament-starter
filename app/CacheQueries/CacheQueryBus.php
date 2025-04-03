<?php

declare(strict_types=1);

namespace App\CacheQueries;

use App\Support\Handler\HandlerHelper;
use Illuminate\Container\Container;

final readonly class CacheQueryBus implements CacheQueryBusInterface
{
    public function __construct(
        private Container $container,
        private HandlerHelper $handlerHelper
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
