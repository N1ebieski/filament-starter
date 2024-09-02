<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Http\Client;

use Illuminate\Http\Client\PendingRequest as BasePendingRequest;
use App\Overrides\Illuminate\Contracts\Http\Client\PendingRequest as PendingRequestContract;

final class PendingRequest implements PendingRequestContract
{
    public function __construct(private readonly BasePendingRequest $request)
    {
    }

    public function async(bool $async = true)
    {
        return $this->request->async($async);
    }

    public function get(string $url, $query = null)
    {
        return $this->request->get($url, $query);
    }

    public function send(string $method, string $url, array $options = [])
    {
        return $this->request->send($method, $url, $options);
    }
}
