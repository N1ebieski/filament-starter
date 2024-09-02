<?php

declare(strict_types=1);

namespace App\Http\Clients;

use GuzzleHttp\Promise\PromiseInterface;

final class AsyncResponse
{
    public function __construct(
        public readonly PromiseInterface $promise,
        public ?Response $response = null
    ) {
    }
}
