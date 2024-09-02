<?php

declare(strict_types=1);

namespace App\Http\Clients;

use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use App\Support\Handler\HandlerHelper;
use App\Overrides\Illuminate\Contracts\Http\Client\PendingRequest;

final class ClientBus implements ClientBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper,
        private readonly PendingRequest $client
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

    /**
     * @param array<int|string, Client> $queries
     * @return array<Response>
     */
    public function executeMany(array $queries): array
    {
        $asyncResponses = new Collection();

        foreach ($queries as $key => $query) {
            $asyncResponses->put($key, $this->execute($query));
        }

        Utils::unwrap($asyncResponses->values()->flatten()->pluck('promise')->toArray());

        $responses = $asyncResponses->map(fn (AsyncResponse $asyncResponse) => $asyncResponse->response);

        return $responses->toArray();
    }

    private function resolveHandler(Client $query): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($query), [
            'client' => $this->client
        ]);
    }
}
