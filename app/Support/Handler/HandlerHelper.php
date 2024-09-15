<?php

declare(strict_types=1);

namespace App\Support\Handler;

use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Support\Attributes\Handler\Handler;

final class HandlerHelper
{
    /** @var array */
    private const NAMES = [
        'Query',
        'CacheQuery',
        'Command',
        'Client',
        'Action'
    ];

    /**
     * Finds and returns the Handler namespace for the specified object.
     *
     * Firstly checks if objact has a Handler attribute.
     *
     * If not, it looks for a Handler with an identical namespace.
     */
    public static function getNamespace(object $class): string
    {
        /** @var class-string */
        $classNamespace = get_class($class);

        $reflectionClass = new ReflectionClass($classNamespace);

        $handlerAttributes = $reflectionClass->getAttributes(Handler::class);

        if (count($handlerAttributes) > 0) {
            /** @var Handler */
            $handlerAttribute = $handlerAttributes[0]->newInstance();

            return $handlerAttribute->class;
        }

        $classBasename = class_basename($class);

        $handlerNamespace = Str::beforeLast($classNamespace, '\\');

        $names = Collection::make(static::NAMES)
            ->map(function (string $name): string {
                return preg_quote($name);
            })
            ->implode('|');

        $handlerName = $classBasename . 'Handler';

        $lastOccurance = Str::match('/(' . $names . ')$/', $classBasename);

        if (!empty($lastOccurance)) {
            $handlerName = Str::replaceLast($lastOccurance, 'Handler', $classBasename);
        }

        $handlerNamespace = $handlerNamespace . '\\' . $handlerName;

        return $handlerNamespace;
    }
}
