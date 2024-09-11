<?php

declare(strict_types=1);

namespace App\Commands;

use App\Queries\QueryBusInterface;
use Illuminate\Database\DatabaseManager as DB;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

/**
 * @method mixed handle(Command $command)
 */
abstract class Handler
{
    public function __construct(
        protected readonly DB $db,
        protected readonly CommandBusInterface $commandBus,
        protected readonly QueryBusInterface $queryBus,
        protected readonly EventDispatcher $eventDispatcher
    ) {
    }
}
