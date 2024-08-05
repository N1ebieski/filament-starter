<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Bus;

interface Dispatcher
{
    /**
     * Temporary fix. @see https://github.com/laravel/framework/issues/45781
     *
     * Dispatch a command to its appropriate handler.
     *
     * @param  mixed  $command
     * @return mixed
     */
    public function dispatch($command);

    /**
     * Dispatch a command to its appropriate handler in the current process without using the synchronous queue.
     *
     * @param  mixed  $command
     * @param  mixed  $handler
     * @return mixed
     */
    public function dispatchNow($command, $handler = null);

    /**
     * Laravel doesn't define chain method in Contract.
     *
     * Create a new chain of queueable jobs.
     *
     * @param  \Illuminate\Support\Collection|array  $jobs
     * @return \Illuminate\Foundation\Bus\PendingChain
     */
    public function chain($jobs);

    /**
     * Laravel doesn't define batch method in Contract.
     *
     * Create a new batch of queueable jobs.
     *
     * @param  \Illuminate\Support\Collection|array|mixed  $jobs
     * @return \Illuminate\Bus\PendingBatch
     */
    public function batch($jobs);
}
