<?php

declare(strict_types=1);

namespace App\Http\Clients;

use GuzzleHttp\Promise\PromiseInterface;

final class AsyncResponse
{
    public PromiseInterface $promise;

    public ?Response $response = null;

    public function __construct(
        PromiseInterface $promise,
        ?Response $response = null
    ) {
        $this->promise = $promise;
        $this->response = $response;
    }
}
