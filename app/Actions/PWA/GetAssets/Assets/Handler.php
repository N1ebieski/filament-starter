<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets\Assets;

use Closure;

abstract class Handler
{
    abstract public function handle(Assets $assets, Closure $next): Assets;
}
