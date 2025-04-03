<?php

declare(strict_types=1);

namespace App\Listeners\Spotlight\FlushCommands;

use App\Listeners\Listener;
use Laravel\Octane\Events\RequestReceived;
use LivewireUI\Spotlight\Spotlight;

final readonly class FlushCommandsListener extends Listener
{
    public function handle(RequestReceived $event): void
    {
        Spotlight::$commands = [];
    }
}
