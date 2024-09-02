<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Http\Client;

use App\Overrides\Illuminate\Contracts\Http\Client\PendingRequest as PendingRequestContract;

interface Factory
{
    public function request(): PendingRequestContract;
}
