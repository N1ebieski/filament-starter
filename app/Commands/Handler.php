<?php

declare(strict_types=1);

namespace App\Commands;

use App\Queries\QueryBus;
use Illuminate\Database\DatabaseManager as DB;

abstract class Handler
{
    public function __construct(
        protected DB $db,
        protected CommandBus $commandBus,
        protected QueryBus $queryBus
    ) {
    }
}
