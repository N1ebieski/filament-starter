<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Filesystem;

use Illuminate\Contracts\Filesystem\Filesystem as BaseFilesystem;

interface Filesystem extends BaseFilesystem
{
    /**
     * Get the full path for the file at the given "short" path.
     *
     * @param  string  $path
     * @return string
     */
    public function path($path);
}
