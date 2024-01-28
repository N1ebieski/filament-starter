<?php

declare(strict_types=1);

namespace App\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use App\Extends\LivewireUI\Spotlight\Spotlight;

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
