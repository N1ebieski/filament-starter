<?php

declare(strict_types=1);

namespace App\Http\Clients;

interface ClientBusInterface
{
    /**
     * @return AsyncResponse|Response
     */
    public function execute(Client $query);
}
