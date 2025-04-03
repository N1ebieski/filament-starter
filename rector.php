<?php

declare(strict_types=1);

use RectorLaravel\Set\LaravelLevelSetList;
use RectorLaravel\Set\LaravelSetList;

return \Rector\Config\RectorConfig::configure()
    ->withPaths([
        __DIR__.'/app',
        __DIR__.'/bootstrap/app.php',
        __DIR__.'/bootstrap/providers.php',
        __DIR__.'/routes',
        __DIR__.'/config',
        __DIR__.'/tests',
        __DIR__.'/database',
    ])
    ->withRules([
        \App\Overrides\Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class,
    ])
    ->withSkip([
        \Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector::class,
        \Rector\Php81\Rector\Array_\FirstClassCallableRector::class,
        \RectorLaravel\Rector\PropertyFetch\ReplaceFakerInstanceWithHelperRector::class,
        \Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector::class,
        \Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class,
        \Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector::class => [
            __DIR__.'/app/Exceptions/Exception.php',
        ],
        \Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector::class => [
            __DIR__.'/app/Exceptions/Exception.php',
        ],
        \Rector\TypeDeclaration\Rector\Closure\ClosureReturnTypeRector::class => [
            __DIR__.'/app/Providers/App/AppServiceProvider.php',
        ],
        \App\Overrides\Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class => [
            __DIR__.'/app/Providers/App/AppServiceProvider.php',
        ],
        \Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector::class => [
            __DIR__.'/app/Http/Middleware/ApplyTenantScope/ApplyTenantScopeMiddleware.php',
            __DIR__.'/app/Http/Middleware/ApplyUserScope/ApplyUserScopeMiddleware.php',
        ],
    ])
    ->withComposerBased(phpunit: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
        carbon: true,
        phpunitCodeQuality: true
    )
    ->withPhpSets()
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_110,
        LaravelSetList::LARAVEL_ARRAYACCESS_TO_METHOD_CALL,
        LaravelSetList::LARAVEL_ARRAY_STR_FUNCTION_TO_STATIC_CALL,
        LaravelSetList::LARAVEL_CODE_QUALITY,
        LaravelSetList::LARAVEL_COLLECTION,
        LaravelSetList::LARAVEL_CONTAINER_STRING_TO_FULLY_QUALIFIED_NAME,
        LaravelSetList::LARAVEL_ELOQUENT_MAGIC_METHOD_TO_QUERY_BUILDER,
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        LaravelSetList::LARAVEL_LEGACY_FACTORIES_TO_CLASSES,
    ]);
