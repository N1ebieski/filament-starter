<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Filesystem;

interface Factory
{
    /**
     * Get a filesystem implementation.
     *
     * @param  string|null  $name
     * @return \App\Overrides\Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk($name = null);
}
