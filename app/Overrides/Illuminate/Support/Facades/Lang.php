<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Support\Facades;

use Illuminate\Support\Facades\Lang as BaseLang;

/**
 * @method static string string(string $key, array $replace = [], string|null $locale = null, bool $fallback = true)
 */
final class Lang extends BaseLang {}
