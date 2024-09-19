<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Http\Client;

interface Client
{
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
