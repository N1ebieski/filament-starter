<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Http\Client;

use Illuminate\Http\Client\PendingRequest as BasePendingRequest;
use App\Overrides\Illuminate\Contracts\Http\Client\Client as PendingRequestContract;

final class PendingRequest implements PendingRequestContract
{
    public function __construct(private readonly BasePendingRequest $request)
    {
    }

    /**
     * Toggle asynchronicity in requests.
     *
     * @param  bool  $async
     * @return $this
     */
    public function async(bool $async = true)
    {
        $this->request->async($async);

        return $this;
    }

    /**
     * Issue a GET request to the given URL.
     *
     * @param  string  $url
     * @param  array|string|null  $query
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function get(string $url, $query = null)
    {
        return $this->request->get($url, $query);
    }

    /**
     * Issue a POST request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function post(string $url, array $data = [])
    {
        return $this->request->post($url, $data);
    }

    /**
     * Send the request to the given URL.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  array  $options
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Exception
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function send(string $method, string $url, array $options = [])
    {
        return $this->request->send($method, $url, $options);
    }
}
