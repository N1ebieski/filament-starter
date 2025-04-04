<?php

namespace App\Providers\Filesystem;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class FilesystemServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->scoped(\App\Overrides\Illuminate\Contracts\Filesystem\Factory::class, function (Application $app) {
            /** @var \Illuminate\Filesystem\FilesystemManager */
            $filesystemManager = $app->make(\Illuminate\Filesystem\FilesystemManager::class);

            return new \App\Overrides\Illuminate\Filesystem\FilesystemManager($filesystemManager);
        });
    }

    public function provides(): array
    {
        return [
            \App\Overrides\Illuminate\Contracts\Filesystem\Factory::class,
        ];
    }
}
