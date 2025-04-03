<?php

declare(strict_types=1);

return \Rector\Config\RectorConfig::configure()
    ->withSets([
        \RectorLaravel\Set\LaravelLevelSetList::UP_TO_LARAVEL_110,
    ]);
