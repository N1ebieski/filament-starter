<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Pipeline;

use Illuminate\Pipeline\Pipeline as BasePipeline;
use App\Overrides\Illuminate\Contracts\Chain\Chain as ContractsChain;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline as ContractsPipeline;

final class Pipeline implements ContractsPipeline, ContractsChain
{
    public function __construct(private readonly BasePipeline $pipeline)
    {
    }

    /**
     * Set the array of pipes.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function through(...$pipes)
    {
        $this->pipeline->through(...$pipes);

        return $this;
    }

    /**
     * Push additional pipes onto the pipeline.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function pipe(...$pipes)
    {
        $this->pipeline->pipe(...$pipes);

        return $this;
    }

    /**
     * @param mixed $traveler
     * @return mixed
     */
    public function process(mixed $traveler)
    {
        return $this->pipeline->send($traveler)->thenReturn();
    }
}
