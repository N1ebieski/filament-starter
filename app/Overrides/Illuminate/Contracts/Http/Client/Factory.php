<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Http\Client;

interface Factory
{
    public function request(): Client;
}
