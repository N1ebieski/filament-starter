<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Bus;

use App\Overrides\Illuminate\Contracts\Bus\Dispatcher as ContractsDispatcher;
use Illuminate\Bus\Dispatcher as BaseDispatcher;
use Illuminate\Foundation\Bus\PendingDispatch;

final class Dispatcher implements ContractsDispatcher
{
    public function __construct(private readonly BaseDispatcher $dispatcher) {}

    /**
     * Laravel doesn't define chain method in Contract.
     *
     * Create a new chain of queueable jobs.
     *
     * @param  \Illuminate\Support\Collection|array  $jobs
     * @return \Illuminate\Foundation\Bus\PendingChain
     */
    public function chain($jobs)
    {
        return $this->dispatcher->chain($jobs);
    }

    /**
     * Laravel doesn't define batch method in Contract.
     *
     * Create a new batch of queueable jobs.
     *
     * @param  \Illuminate\Support\Collection|array|mixed  $jobs
     * @return \Illuminate\Bus\PendingBatch
     */
    public function batch($jobs)
    {
        return $this->dispatcher->batch($jobs);
    }

    /**
     * Temporary fix. @see https://github.com/laravel/framework/issues/45781
     *
     * Dispatch a command to its appropriate handler.
     *
     * @param  mixed  $command
     */
    public function dispatch($command): PendingDispatch
    {
        return new PendingDispatch($command);
    }

    /**
     * Dispatch a command to its appropriate handler in the current process without using the synchronous queue.
     *
     * @param  mixed  $command
     * @param  mixed  $handler
     * @return mixed
     */
    public function dispatchNow($command, $handler = null)
    {
        return $this->dispatcher->dispatchNow($command, $handler);
    }
}
