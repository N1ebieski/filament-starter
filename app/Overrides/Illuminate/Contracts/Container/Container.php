<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Container;

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
