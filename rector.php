<?php

declare(strict_types=1);

use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelLevelSetList;

return \Rector\Config\RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap',
        __DIR__.'/routes',
        __DIR__.'/config',
        __DIR__.'/tests',
        __DIR__.'/database',
    ])
    ->withRules([
        //
    ])
    ->withSkip([
        \RectorLaravel\Rector\PropertyFetch\ReplaceFakerInstanceWithHelperRector::class,
        \Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector::class => [
            __DIR__.'/app/Http/Middleware/ApplyTenantScope/ApplyTenantScopeMiddleware.php',
            __DIR__.'/app/Http/Middleware/ApplyUserScope/ApplyUserScopeMiddleware.php',
        ],
    ])
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);
