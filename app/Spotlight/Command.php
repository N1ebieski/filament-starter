<?php

declare(strict_types=1);

namespace App\Spotlight;

use Illuminate\Contracts\Support\Arrayable;
use LivewireUI\Spotlight\SpotlightCommand;
use Override;

abstract class Command extends SpotlightCommand implements Arrayable
{
    protected bool $isDefault = false;

    protected ?string $icon = null;

    protected bool $isActive = false;

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'icon' => $this->getIcon(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'synonyms' => $this->getSynonyms(),
            'dependencies' => $this->dependencies()?->toArray() ?? [],
            'isDefault' => $this->isDefault(),
            'isActive' => $this->isActive(),
        ];
    }
}
