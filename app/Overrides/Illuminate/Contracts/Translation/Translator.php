<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Translation;

interface Translator
{
    /**
     * Get the translation for the given key as string.
     *
     * @param  string  $key
     * @param  string|null  $locale
     * @param  bool  $fallback
     */
    public function string($key, array $replace = [], $locale = null, $fallback = true): string;
}
