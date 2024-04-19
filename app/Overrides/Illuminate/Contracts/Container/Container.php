<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Container;

use Illuminate\Contracts\Container\Container as BaseContainer;

interface Container
{
    /**
     * Resolve the given types from the container.
     *
     * @param  array<string>  $abstracts
     * @param  array  $parameters
     * @return array<object>
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function makeMany(array $abstracts, array $parameters = []): array;
}
