<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight\Actions;

use Filament\Panel;
use LivewireUI\Spotlight\Spotlight;
use Filament\Resources\Pages\PageRegistration;
use App\Overrides\Pxlrbt\FilamentSpotlight\Commands\ResourceCommand;

class RegisterResources
{
    public static function boot(Panel $panel)
    {
        $resources = $panel->getResources();

        foreach ($resources as $resource) {
            $pages = $resource::getPages();

            foreach ($pages as $key => $page) {
                /**
                 * @var PageRegistration $page
                 */
                if (blank($key) || blank($page->getPage())) {
                    continue;
                }

                $command = new ResourceCommand(
                    resource: $resource,
                    page: $page->getPage(),
                    key: $key,
                );

                Spotlight::$commands[$command->getId()] = $command;
            }
        }
    }
}
