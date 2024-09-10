<?php

namespace App\Overrides\Pxlrbt\FilamentSpotlight\Actions;

use Filament\Panel;
use Filament\Pages\Page;
use LivewireUI\Spotlight\Spotlight;
use App\Overrides\Pxlrbt\FilamentSpotlight\Commands\PageCommand;

class RegisterPages
{
    public static function boot(Panel $panel): void
    {
        $pages = $panel->getPages();

        foreach ($pages as $pageClass) {

            /**
             * @var Page $page
             */
            $page = new $pageClass();

            if (method_exists($page, 'shouldRegisterSpotlight') && $page->shouldRegisterSpotlight() === false) {
                continue;
            }

            $name = collect([
                $page->getNavigationGroup(),
                $page->getTitle(),
            ])->filter()->join(' / ');

            $url = $page::getUrl();

            if (blank($name) || blank($url)) {
                continue;
            }

            $command = new PageCommand(
                name: $name,
                url: $url
            );

            Spotlight::$commands[$command->getId()] = $command;
        }
    }
}
