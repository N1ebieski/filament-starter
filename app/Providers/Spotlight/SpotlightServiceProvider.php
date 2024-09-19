<?php

declare(strict_types=1);

namespace App\Providers\Spotlight;

use App\Overrides\LivewireUI\Spotlight\Spotlight;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class SpotlightServiceProvider extends ServiceProvider
{
    protected array $commands = [];

    public function boot(): void
    {
        Livewire::component('livewire-ui-spotlight', Spotlight::class);

        foreach ($this->commands as $command) {
            Spotlight::registerCommand($command);
        }
    }
}
