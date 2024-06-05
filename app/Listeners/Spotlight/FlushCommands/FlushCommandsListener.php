<?php

declare(strict_types=1);

namespace App\Listeners\Spotlight\FlushCommands;

use App\Listeners\Listener;
use LivewireUI\Spotlight\Spotlight;
use Laravel\Octane\Events\RequestReceived;

final class FlushCommandsListener extends Listener
{
    public function handle(RequestReceived $event): void
    {
        Spotlight::$commands = [];
    }
}
