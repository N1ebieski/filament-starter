<?php

declare(strict_types=1);

namespace App\Mixins\Translator;

use Illuminate\Support\Facades\App;

final class TranslatorMixin
{
    public function string(): \Closure
    {
        return function (string $key, array $replace = [], ?string $locale = null) {
            $translator = App::make(\App\Overrides\Illuminate\Contracts\Translation\Translator::class);

            return $translator->string($key, $replace, $locale);
        };
    }
}
