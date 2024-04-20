<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Chain;

use Override;
use Illuminate\Pipeline\Pipeline as BasePipeline;
use App\Overrides\Illuminate\Contracts\Chain\Chain as ChainContract;

final class Chain extends BasePipeline implements ChainContract
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
