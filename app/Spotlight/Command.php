<?php

declare(strict_types=1);

namespace App\Spotlight;

use Illuminate\Contracts\Support\Arrayable;
use LivewireUI\Spotlight\SpotlightCommand;
use Override;

abstract class Command extends SpotlightCommand implements Arrayable
{
    protected bool $default = false;

    public function getDefault(): bool
    {
        return $this->default;
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'synonyms' => $this->getSynonyms(),
            'dependencies' => $this->dependencies()?->toArray() ?? [],
            'default' => $this->getDefault(),
        ];
    }
}
