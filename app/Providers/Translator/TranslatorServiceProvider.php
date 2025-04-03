<?php

declare(strict_types=1);

namespace App\Providers\Translator;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\Facades\Lang;

final class TranslatorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Overrides\Illuminate\Contracts\Translation\Translator::class, function (Application $app) {
            return new \App\Overrides\Illuminate\Translation\Translator(
                $app->make(\Illuminate\Translation\Translator::class)
            );
        });
    }

    public function boot(): void
    {
        Lang::mixin(new \App\Mixins\Translator\TranslatorMixin);
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Translation\Translator::class,
        ];
    }
}
