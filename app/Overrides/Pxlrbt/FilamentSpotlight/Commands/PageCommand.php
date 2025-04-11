<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight\Commands;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\App;
use LivewireUI\Spotlight\Spotlight;
use Override;
use pxlrbt\FilamentSpotlight\Commands\PageCommand as BasePageCommand;

final class PageCommand extends BasePageCommand implements Arrayable
{
    public function __construct(
        protected string $name,
        protected string $url,
        protected string|Htmlable|null $icon = null,
        protected bool $shouldBeShown = true,
    ) {
        //
    }

    public function getIcon(): ?string
    {
        /** @var Factory $bladeUIFactory */
        $bladeUIFactory = App::make(Factory::class);

        $icon = $this->icon;

        if ($this->icon instanceof Htmlable) {
            $icon = $this->icon->toHtml();
        }

        return $icon ? $bladeUIFactory->svg($this->icon)->toHtml() : null;
    }

    #[Override]
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect($this->url, navigate: true);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'icon' => $this->getIcon(),
            'description' => $this->getDescription(),
        ];
    }
}
