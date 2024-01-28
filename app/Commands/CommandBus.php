<?php

declare(strict_types=1);

namespace App\Commands;

use Illuminate\Container\Container;
use App\Support\Handler\HandlerHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Support\Handler\IncorrectNameException;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Container\BindingResolutionException;

final class CommandBus
{
    public function __construct(
        private readonly Container $container,
        private readonly BusDispatcher $busDispatcher,
        private readonly JobFactory $jobFactory,
        private readonly HandlerHelper $handlerHelper
    ) {
    }

    /**
     * @param Command $command
     * @return mixed
     * @throws IncorrectNameException
     * @throws BindingResolutionException
     */
    public function execute(Command $command)
    {
        $handler = $this->resolveHandler($command);

        if (!$handler instanceof ShouldQueue) {
            //@phpstan-ignore-next-line
            return $handler->handle($command);
        }

        $this->dispatch($command);
    }

    public function dispatch(Command $command): void
    {
        $job = $this->jobFactory->make($command);

        $this->busDispatcher->dispatch($job);
    }

    public function dispatchSync(Command $command): void
    {
        $job = $this->jobFactory->make($command);

        $this->busDispatcher->dispatchSync($job);
    }

    private function resolveHandler(Command $command): Handler
    {
        return $this->container->make($this->handlerHelper->getNamespace($command));
    }
}
