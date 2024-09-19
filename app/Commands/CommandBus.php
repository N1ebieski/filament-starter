<?php

declare(strict_types=1);

namespace App\Commands;

use App\Support\Handler\HandlerHelper;
use Illuminate\Container\Container;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;

final class CommandBus implements CommandBusInterface
{
    public function __construct(
        private readonly Container $container,
        private readonly BusDispatcher $busDispatcher,
        private readonly JobFactory $jobFactory,
        private readonly HandlerHelper $handlerHelper
    ) {}

    /**
     * @return mixed
     */
    public function execute(Command $command)
    {
        $handler = $this->resolveHandler($command);

        if (! $handler instanceof ShouldQueue) {
            return $handler->handle($command);
        }

        $this->dispatch($command);
    }

    public function dispatch(Command $command): void
    {
        $job = $this->jobFactory->makeJob($command);

        $this->busDispatcher->dispatch($job);
    }

    public function dispatchSync(Command $command): void
    {
        $job = $this->jobFactory->makeJob($command);

        $this->busDispatcher->dispatchSync($job);
    }

    private function resolveHandler(Command $command): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($command));
    }
}
