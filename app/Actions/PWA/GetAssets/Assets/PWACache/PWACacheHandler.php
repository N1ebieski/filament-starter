<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets\Assets\PWACache;

use App\Actions\PWA\GetAssets\Assets\Assets;
use App\Actions\PWA\GetAssets\Assets\Handler;
use App\Support\PWA\PWACacheInterface;
use Closure;

final class PWACacheHandler extends Handler
{
    public function handle(Assets $assets, Closure $next): Assets
    {
        foreach (get_declared_classes() as $class) {
            if (in_array(PWACacheInterface::class, class_implements($class))) {
                /** @var PWACacheInterface $class */
                $assets->value->push($class::getUrlForPWA());
            }
        }

        return $next($assets);
    }
}
