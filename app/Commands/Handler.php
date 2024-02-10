<?php

declare(strict_types=1);

namespace App\Commands;

use App\Queries\QueryBus;
use Illuminate\Database\DatabaseManager as DB;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

abstract class Handler
{
    public function __construct(
        protected readonly DB $db,
        protected readonly CommandBus $commandBus,
        protected readonly QueryBus $queryBus,
        protected readonly EventDispatcher $eventDispatcher
    ) {
    }
}
