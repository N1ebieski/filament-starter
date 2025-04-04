<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Config;

interface Repository
{
    /**
     * Get the specified string configuration value.
     *
     * @param  (\Closure():(string|null))|string|null  $default
     */
    public function string(string $key, $default = null): string;

    /**
     * Get the specified integer configuration value.
     *
     * @param  (\Closure():(int|null))|int|null  $default
     */
    public function integer(string $key, $default = null): int;

    /**
     * Get the specified float configuration value.
     *
     * @param  (\Closure():(float|null))|float|null  $default
     */
    public function float(string $key, $default = null): float;

    /**
     * Get the specified boolean configuration value.
     *
     * @param  (\Closure():(bool|null))|bool|null  $default
     */
    public function boolean(string $key, $default = null): bool;

    /**
     * Get the specified array configuration value.
     *
     * @param  (\Closure():(array<array-key, mixed>|null))|array<array-key, mixed>|null  $default
     * @return array<array-key, mixed>
     */
    public function array(string $key, $default = null): array;
}
