<?php

declare(strict_types=1);

namespace App\Commands;

use App\Support\Data\Data;
use Illuminate\Contracts\Support\Arrayable;

abstract class Command extends Data implements Arrayable
{
}
