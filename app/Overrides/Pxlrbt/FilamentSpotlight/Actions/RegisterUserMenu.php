<?php

declare(strict_types=1);

namespace App\Overrides\Pxlrbt\FilamentSpotlight\Actions;

use App\Overrides\Pxlrbt\FilamentSpotlight\Commands\PageCommand;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use LivewireUI\Spotlight\Spotlight;
use pxlrbt\FilamentSpotlight\Actions\RegisterUserMenu as BaseRegisterUserMenu;

final class RegisterUserMenu extends BaseRegisterUserMenu
{
    public static function boot(Panel $panel): void
    {
        $self = new self;
        /**
         * @var array<MenuItem> $items
         */
        $items = $panel->getUserMenuItems();

        foreach ($items as $key => $item) {
            $name = $self->getName($key, $item);
            $url = $self->getUrl($key, $item);
            if (blank($name)) {
                continue;
            }

            if (blank($url)) {
                continue;
            }

            $command = new PageCommand(
                name: $name,
                url: $url,
                icon: $item->getIcon(),
            );

            Spotlight::$commands[$command->getId()] = $command;
        }
    }

    protected function getName(string|int $key, MenuItem $item): ?string
    {
        return parent::getName((string) $key, $item);
    }

    protected function getUrl(string|int $key, MenuItem $item): ?string
    {
        return parent::getUrl((string) $key, $item);
    }
}
