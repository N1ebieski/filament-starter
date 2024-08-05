<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Filesystem;

use App\Overrides\Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Filesystem\FilesystemManager as BaseFilesystemManager;

final class FilesystemManager implements Factory
{
    public function __construct(private readonly BaseFilesystemManager $filesystemManager)
    {
    }

    /**
     * Get a filesystem implementation.
     *
     * @param  string|null  $name
     * @return \App\Overrides\Illuminate\Contracts\Filesystem\Filesystem
     */
    public function disk($name = null)
    {
        /** @var \App\Overrides\Illuminate\Contracts\Filesystem\Filesystem */
        $filesystem = $this->filesystemManager->disk($name);

        return $filesystem;
    }
}
