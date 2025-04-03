<?php

declare(strict_types=1);

namespace App\Providers\Spotlight;

use App\Overrides\LivewireUI\Spotlight\Spotlight;
use App\Providers\ServiceProvider;
use Livewire\Livewire;

final class SpotlightServiceProvider extends ServiceProvider
{
    private array $commands = [];

    public function boot(): void
    {
        Livewire::component('livewire-ui-spotlight', Spotlight::class);

        foreach ($this->commands as $command) {
            Spotlight::registerCommand($command);
        }
    }
}
