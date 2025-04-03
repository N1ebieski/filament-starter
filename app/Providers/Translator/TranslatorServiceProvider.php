<?php

declare(strict_types=1);

namespace App\Providers\Translator;

use App\Providers\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

final class TranslatorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Translation\Translator::class, function (Application $app) {
            return new \App\Overrides\Illuminate\Translation\Translator(
                $app->make(\Illuminate\Translation\Translator::class)
            );
        });

        $this->app->resolving(\Illuminate\Translation\Translator::class, function (\Illuminate\Translation\Translator $translator) {
            $translator->mixin(new \App\Mixins\Translator\TranslatorMixin);
        });
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Translation\Translator::class,
        ];
    }
}
