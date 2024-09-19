<?php

declare(strict_types=1);

namespace App\Http\Clients;

interface ClientBusInterface
{
    /**
     * @return AsyncResponse|Response
     */
    public function execute(Client $client);

    /**
     * @param  array<int|string, Client>  $clients
     * @return array<Response>
     */
    public function executeMany(array $clients): array;
}
