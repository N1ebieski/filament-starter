<?php

declare(strict_types=1);

namespace App\Http\Clients;

use GuzzleHttp\ClientInterface;

abstract class Handler
{
    public function __construct(protected readonly ClientInterface $clientInterface)
    {
    }
}
