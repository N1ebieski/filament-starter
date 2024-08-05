<?php

declare(strict_types=1);

namespace App\Queries;

use Illuminate\Support\Carbon;
use Illuminate\Contracts\Auth\Guard as Auth;
use Illuminate\Database\DatabaseManager as DB;
use Illuminate\Contracts\Config\Repository as Config;

abstract class Handler
{
    public function __construct(
        protected readonly Config $config,
        protected readonly Carbon $carbon,
        protected readonly Auth $auth,
        protected readonly DB $db
    ) {
    }
}
