<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Container;

use Override;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container as BaseContainer;
use App\Overrides\Illuminate\Contracts\Container\Container as ContainerContract;

final class Container implements ContainerContract
{
    public function __construct(private readonly BaseContainer $baseContainer)
    {
    }

    #[Override]
    public function makeMany(array $abstracts, array $parameters = []): array
    {
        $objects = new Collection();

        foreach ($abstracts as $abstract) {
            $objects->push($this->baseContainer->make($abstract, $parameters));
        }

        return $objects->toArray();
    }
}
