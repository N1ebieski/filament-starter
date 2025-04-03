<?php

declare(strict_types=1);

namespace App\Overrides\LivewireUI\Spotlight;

use App\Spotlight\Command;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Computed;
use LivewireUI\Spotlight\Spotlight as BaseSpotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use Override;

final class Spotlight extends BaseSpotlight
{
    private Config $config;

    private Collection $collection;

    private Container $container;

    public function boot(
        Config $config,
        Collection $collection,
        Container $container
    ): void {
        $this->config = $config;
        $this->collection = $collection;
        $this->container = $container;
    }

    #[Override]
    public static function registerCommand(string $command): void
    {
        tap(App::make($command), function (Command $command): void {
            if ($command->getDefault() && is_null($command->dependencies())) {
                throw new \Exception('A command without dependencies cannot be default.');
            }

            parent::$commands[] = $command;
        });
    }

    #[Computed()]
    public function shortcutsAsString(): string
    {
        $shortcuts = $this->config->get('livewire-ui-spotlight.shortcuts');

        return mb_strtoupper('CTRL+'.$shortcuts[0]);
    }

    #[Override]
    public function render(): View|Factory
    {
        /** @var View */
        $view = parent::render();

        $view->with(
            'commands',
            $this->collection->make(self::$commands)
                ->filter(function (SpotlightCommand $command) {
                    if (! method_exists($command, 'shouldBeShown')) {
                        return true;
                    }

                    return $this->container->call([$command, 'shouldBeShown']);
                })
                ->values()
                ->map(function (SpotlightCommand $command, int $key) use ($view) {
                    if ($command instanceof Command) {
                        return $command->toArray();
                    }

                    return $view->getData()['commands'][$key];
                })
        );

        return $view;
    }
}
