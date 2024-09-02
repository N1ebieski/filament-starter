<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Http\Client;

interface PendingRequest
{
    /**
     * Toggle asynchronicity in requests.
     *
     * @param  bool  $async
     * @return $this
     */
    public function async(bool $async = true);

    /**
     * Issue a GET request to the given URL.
     *
     * @param  string  $url
     * @param  array|string|null  $query
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Illuminate\Http\Client\ConnectionException
     */
    public function get(string $url, $query = null);

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
    public function send(string $method, string $url, array $options = []);
}
