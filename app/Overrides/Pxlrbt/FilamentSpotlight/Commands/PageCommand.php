<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight\Commands;

use LivewireUI\Spotlight\Spotlight;
use Override;
use pxlrbt\FilamentSpotlight\Commands\PageCommand as BasePageCommand;

final class PageCommand extends BasePageCommand
{
    #[Override]
    public function execute(Spotlight $spotlight): void
    {
        $spotlight->redirect($this->url, navigate: true);
    }
}
