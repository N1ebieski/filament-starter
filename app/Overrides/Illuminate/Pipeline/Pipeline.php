<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Pipeline;

use Override;
use Illuminate\Pipeline\Pipeline as BasePipeline;
use App\Overrides\Illuminate\Contracts\Pipeline\Pipeline as PipelineContract;

final class Pipeline extends BasePipeline implements PipelineContract
{
    /**
     * @param mixed $traveler
     * @return mixed
     */
    #[Override]
    public function process(mixed $traveler)
    {
        return $this->send($traveler)->thenReturn();
    }
}
