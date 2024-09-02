<?php

declare(strict_types=1);

namespace App\Http\Clients;

interface ClientBusInterface
{
    /**
     * @return AsyncResponse|Response
     */
    public function execute(Client $query);

    /**
     * @param array<int|string, Client> $queries
     * @return array<Response>
     */
    public function executeMany(array $queries): array;
}
