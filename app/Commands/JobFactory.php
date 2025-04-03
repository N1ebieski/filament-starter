<?php

declare(strict_types=1);

namespace App\Commands;

use App\Support\Handler\HandlerHelper;
use Illuminate\Events\CallQueuedListener;

final readonly class JobFactory
{
    public function __construct(private HandlerHelper $handlerHelper) {}

    public function makeJob(Command $command): CallQueuedListener
    {
        return new CallQueuedListener(
            class: $this->handlerHelper->getNamespace($command),
            method: 'handle',
            data: [$command]
        );
    }
}
