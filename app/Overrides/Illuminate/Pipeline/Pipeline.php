<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Pipeline;

use Illuminate\Pipeline\Pipeline as BasePipeline;
use App\Overrides\Illuminate\Contracts\Chain\Chain as ChainContract;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;

final class Pipeline extends BasePipeline implements PipelineContract, ChainContract
{
    /**
     * @param mixed $traveler
     * @return mixed
     */
    public function process(mixed $traveler)
    {
        return $this->send($traveler)->thenReturn();
    }
}
