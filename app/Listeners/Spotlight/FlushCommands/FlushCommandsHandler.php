<?php

declare(strict_types=1);

namespace App\Listeners\Spotlight\FlushCommands;

use LivewireUI\Spotlight\Spotlight;
use Laravel\Octane\Events\RequestReceived;

final class FlushCommandsHandler
{
    public function handle(RequestReceived $event): void
    {
        Spotlight::$commands = [];
    }
}
