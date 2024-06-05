<?php

declare(strict_types=1);

namespace App\Http\Clients;

use App\Support\Arrayable\HasToArray;
use Illuminate\Contracts\Support\Arrayable;

abstract class Client implements Arrayable
{
    use HasToArray;
}
