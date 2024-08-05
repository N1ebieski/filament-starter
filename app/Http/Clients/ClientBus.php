<?php

declare(strict_types=1);

namespace App\Http\Clients;

use Illuminate\Container\Container;
use App\Support\Handler\HandlerHelper;

final class ClientBus implements ClientBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper
    ) {
    }

    /**
     * @return AsyncResponse|Response
     */
    public function execute(Client $query)
    {
        $handler = $this->resolveHandler($query);

        //@phpstan-ignore-next-line
        /** @disregard */
        return $handler->handle($query);
    }

    private function resolveHandler(Client $query): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($query));
    }
}
