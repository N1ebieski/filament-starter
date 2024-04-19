<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Chain;

interface Chain
{
    /**
     * @param mixed $traveler
     * @return mixed
     */
    public function process(mixed $traveler);

    /**
     * Push additional pipes onto the chain.
     *
     * @param  array|mixed  $pipes
     * @return $this
     */
    public function pipe($pipes);

    /**
     * Set the stops of the chain.
     *
     * @param  mixed $stops
     * @return $this
     */
    public function through($stops);
}
