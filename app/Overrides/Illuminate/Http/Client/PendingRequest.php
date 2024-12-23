<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Http\Client;

use App\Overrides\Illuminate\Contracts\Http\Client\Client as PendingRequestContract;
use Illuminate\Http\Client\PendingRequest as BasePendingRequest;

final class PendingRequest implements PendingRequestContract
{
    public function __construct(private readonly BasePendingRequest $request) {}

    /**
     * Specify an authorization token for the request.
     *
     * @param  string  $token
     * @param  string  $type
     * @return $this
     */
    public function withToken($token, $type = 'Bearer')
    {
        $this->request->withToken($token, $type);

        return $this;
    }

    /**
     * Set the base URL for the pending request.
     *
     * @return $this
     */
    public function baseUrl(string $url)
    {
        $this->request->baseUrl($url);

        return $this;
    }

    /**
     * Toggle asynchronicity in requests.
     *
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
