<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Translation;

use App\Overrides\Illuminate\Contracts\Translation\Translator as TranslatorContract;
use InvalidArgumentException;

final readonly class Translator implements TranslatorContract
{
    public function __construct(private \Illuminate\Translation\Translator $baseTranslator) {}

    /**
     * @param  string  $key
     * @param  string|null  $locale
     * @param  bool  $fallback
     */
    public function string($key, array $replace = [], $locale = null, $fallback = true): string
    {
        $value = $this->baseTranslator->get($key, $replace, $locale, $fallback);

        if (! is_string($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Translation value for key [%s] for locale [%s] must be a string, %s given.',
                    $key,
                    $locale,
                    gettype($value)
                )
            );
        }

        return $value;
    }
}
