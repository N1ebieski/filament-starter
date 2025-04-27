<?php

declare(strict_types=1);

namespace App\Mixins\Translator;

use App\Mixins\Mixin;
use Illuminate\Support\Facades\App;

final class TranslatorMixin extends Mixin
{
    public function string(): \Closure
    {
        return function (string $key, array $replace = [], ?string $locale = null): string {
            /** @var \App\Overrides\Illuminate\Translation\Translator $translator */
            $translator = App::make(\App\Overrides\Illuminate\Contracts\Translation\Translator::class);

            return $translator->string($key, $replace, $locale);
        };
    }
}
