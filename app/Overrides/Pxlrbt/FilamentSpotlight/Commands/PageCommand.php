<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight\Commands;

use App\Models\Tenant\Tenant;
use BladeUI\Icons\Factory;
use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Uri;
use LivewireUI\Spotlight\Spotlight;
use Override;
use pxlrbt\FilamentSpotlight\Commands\PageCommand as BasePageCommand;

final class PageCommand extends BasePageCommand implements Arrayable
{
    public function __construct(
        protected string $name,
        protected string $url,
        protected bool $shouldBeShown = true,
        private readonly string|Htmlable|null $icon = null,
    ) {
        //
    }

    public function getIcon(): ?string
    {
        /** @var Factory $bladeUIFactory */
        $bladeUIFactory = App::make(Factory::class);

        $icon = $this->icon;

        if ($icon instanceof Htmlable) {
            $icon = $icon->toHtml();
        }

        return $icon ? $bladeUIFactory->svg($icon)->toHtml() : null;
    }

    public function isActive(): bool
    {
        $currentPath = Uri::of(URL::current())->path();
        $urlPath = Uri::of($this->url)->path();
        $panelHomePath = Uri::of(Filament::getCurrentPanel()?->getHomeUrl() ?? '')->path();

        // Override for Filament Panel Home Page with Tenancy. In this case, the urlPath is '/panel',
        // but the currentPath is '/panel/tenants/1'
        if ($panelHomePath === $urlPath && Filament::hasTenancy()) {
            /** @var string|null $tenantRoutePrefix */
            $tenantRoutePrefix = Filament::getCurrentPanel()?->getTenantRoutePrefix();

            if ($tenantRoutePrefix !== null) {
                /** @var Tenant $tenant */
                $tenant = Filament::getTenant();

                $urlPath = "{$urlPath}/{$tenantRoutePrefix}/{$tenant->id}";
            }
        }

        return $urlPath === $currentPath;
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
            'synonyms' => $this->getSynonyms(),
            'dependencies' => [],
            'isActive' => $this->isActive(),
        ];
    }
}
