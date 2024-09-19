<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Http\Client;

use App\Overrides\Illuminate\Contracts\Http\Client\Client;
use App\Overrides\Illuminate\Contracts\Http\Client\Factory as ClientFactory;
use Illuminate\Http\Client\Factory as BaseFactory;

final class Factory implements ClientFactory
{
    public function __construct(private readonly BaseFactory $factory) {}

    public function request(): Client
    {
        $request = $this->factory->createPendingRequest();

        return new PendingRequest($request);
    }
}
