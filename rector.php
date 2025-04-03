<?php

declare(strict_types=1);

return \Rector\Config\RectorConfig::configure()
    ->withRules([
        \RectorLaravel\Set\LaravelLevelSetList::UP_TO_LARAVEL_110,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true
    );
