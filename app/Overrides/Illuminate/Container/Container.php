<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Container;

use App\Overrides\Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Contracts\Container\Container as BaseContainer;
use Illuminate\Support\Collection;
use Override;

final readonly class Container implements ContainerContract
{
    public function __construct(private BaseContainer $baseContainer) {}

    #[Override]
    public function makeMany(array $abstracts, array $parameters = []): array
    {
        $objects = new Collection;

        foreach ($abstracts as $key => $abstract) {
            $objects->put($key, $this->baseContainer->make($abstract, $parameters));
        }

        return $objects->toArray();
    }
}
