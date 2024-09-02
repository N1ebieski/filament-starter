<?php

declare(strict_types=1);

namespace App\Http\Clients;

use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use App\Support\Handler\HandlerHelper;
use App\Overrides\Illuminate\Contracts\Http\Client\Client as HttpClient;

final class ClientBus implements ClientBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly HandlerHelper $handlerHelper,
        private readonly HttpClient $client
    ) {
    }

    /**
     * @return AsyncResponse|Response
     */
    public function execute(Client $client)
    {
        $handler = $this->resolveHandler($client);

        //@phpstan-ignore-next-line
        /** @disregard */
        return $handler->handle($client);
    }

    /**
     * @param array<int|string, Client> $clients
     * @return array<Response>
     */
    public function executeMany(array $clients): array
    {
        $asyncResponses = new Collection();

        foreach ($clients as $key => $client) {
            $asyncResponses->put($key, $this->execute($client));
        }

        Utils::unwrap($asyncResponses->values()->flatten()->pluck('promise')->toArray());

        $responses = $asyncResponses->map(fn (AsyncResponse $asyncResponse): Response => $asyncResponse->response);

        return $responses->toArray();
    }

    private function resolveHandler(Client $client): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($client), [
            'client' => $this->client
        ]);
    }
}
