<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Chain;

interface Chain
{
    /**
     * @return mixed
     */
    public function process(mixed $traveler);

    /**
     * Push additional pipes onto the chain.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function pipe(...$pipes);

    /**
     * Set the array of pipes.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function through(...$pipes);
}
