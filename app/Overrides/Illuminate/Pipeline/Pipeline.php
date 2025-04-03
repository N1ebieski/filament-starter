<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Pipeline;

use App\Overrides\Illuminate\Contracts\Chain\Chain as ContractsChain;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline as ContractsPipeline;
use Illuminate\Pipeline\Pipeline as BasePipeline;

final readonly class Pipeline implements ContractsChain, ContractsPipeline
{
    public function __construct(private BasePipeline $pipeline) {}

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
     * @return mixed
     */
    public function process(mixed $traveler)
    {
        return $this->pipeline->send($traveler)->thenReturn();
    }
}
