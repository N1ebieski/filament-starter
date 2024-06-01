<?php

declare(strict_types=1);

namespace App\Queries;

use App\Support\Arrayable\HasToArray;
use Illuminate\Contracts\Support\Arrayable;

abstract class Query implements Arrayable
{
    use HasToArray;
}
