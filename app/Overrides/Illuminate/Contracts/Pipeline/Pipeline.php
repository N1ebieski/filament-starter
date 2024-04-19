<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Pipeline;

use Illuminate\Contracts\Pipeline\Pipeline as BasePipeline;

interface Pipeline extends BasePipeline
{
    /**
     * Set the stops of the pipe.
     *
     * @param  mixed $stops
     * @return $this
     */
    public function through($stops);

    /**
     * @param mixed $traveler
     * @return mixed
     */
    public function process(mixed $traveler);
}
