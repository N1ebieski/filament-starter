<?php

declare(strict_types=1);

namespace App\Providers\Translator;

use App\Providers\ServiceProvider;

final class TranslatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(DeferrableServiceProvider::class);

        $this->app->resolving(\Illuminate\Translation\Translator::class, function (\Illuminate\Translation\Translator $translator) {
            $translator->mixin(new \App\Mixins\Translator\TranslatorMixin);
        });
    }
}
