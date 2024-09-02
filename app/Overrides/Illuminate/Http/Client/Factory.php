<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Http\Client;

use Illuminate\Http\Client\Factory as BaseFactory;
use App\Overrides\Illuminate\Http\Client\PendingRequest;
use App\Overrides\Illuminate\Contracts\Http\Client\Factory as ClientFactory;
use App\Overrides\Illuminate\Contracts\Http\Client\PendingRequest as PendingRequestContract;

final class Factory implements ClientFactory
{
    public function __construct(private readonly BaseFactory $factory)
    {
    }

    public function request(): PendingRequestContract
    {
        $request = $this->factory->createPendingRequest();

        return new PendingRequest($request);
    }
}
