<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Pipeline;

interface Pipeline
{
    /**
     * Set the array of pipes.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function through(...$pipes);

    /**
     * @return mixed
     */
    public function process(mixed $traveler);
}
