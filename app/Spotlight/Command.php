<?php

declare(strict_types=1);

namespace App\Spotlight;

use LivewireUI\Spotlight\SpotlightCommand;

abstract class Command extends SpotlightCommand
{
    protected bool $default = false;

    public function getDefault(): bool
    {
        return $this->default;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'synonyms' => $this->getSynonyms(),
            'dependencies' => $this->dependencies()?->toArray() ?? [],
            'default' => $this->getDefault()
        ];
    }
}
