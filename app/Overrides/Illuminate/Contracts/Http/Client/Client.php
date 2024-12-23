<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Http\Client;

interface Client
{
    /**
     * Specify an authorization token for the request.
     *
     * @param  string  $token
     * @param  string  $type
     * @return $this
     */
    public function withToken($token, $type = 'Bearer');

    /**
     * Set the base URL for the pending request.
     *
     * @return $this
     */
    public function baseUrl(string $url);

    /**
     * Toggle asynchronicity in requests.
     *
     * @return $this
     */
    public function async(bool $async = true);

    /**
     * Issue a GET request to the given URL.
     *
     * @param  array|string|null  $query
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function get(string $url, $query = null);

    /**
     * Issue a POST request to the given URL.
     *
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function post(string $url, array $data = []);

    /**
     * Send the request to the given URL.
     *
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Exception
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function send(string $method, string $url, array $options = []);
}
