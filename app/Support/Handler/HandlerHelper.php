<?php

declare(strict_types=1);

namespace App\Support\Handler;

use App\Support\Attributes\Handler\Handler;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;

final readonly class HandlerHelper
{
    /** @var array */
    private const NAMES = [
        'Query',
        'CacheQuery',
        'Command',
        'Client',
        'Action',
    ];

    /**
     * Finds and returns the Handler namespace for the specified object.
     *
     * Firstly checks if objact has a Handler attribute.
     *
     * If not, it looks for a Handler with an identical namespace.
     *
     * @return class-string
     */
    public static function getNamespace(object $class): string
    {
        $classNamespace = $class::class;

        $reflectionClass = new ReflectionClass($classNamespace);

        $handlerAttributes = $reflectionClass->getAttributes(Handler::class);

        if ($handlerAttributes !== []) {
            /** @var Handler */
            $handlerAttribute = $handlerAttributes[0]->newInstance();

            /** @var class-string */
            return $handlerAttribute->class;
        }

        $classBasename = class_basename($class);

        $handlerNamespace = Str::beforeLast($classNamespace, '\\');

        $names = Collection::make(self::NAMES)
            ->map(fn (string $name): string => preg_quote($name))
            ->implode('|');

        $handlerName = $classBasename.'Handler';

        $lastOccurance = Str::match('/('.$names.')$/', $classBasename);

        if (! empty($lastOccurance)) {
            $handlerName = Str::replaceLast($lastOccurance, 'Handler', $classBasename);
        }

        /** @var class-string */
        $handlerNamespace = $handlerNamespace.'\\'.$handlerName;

        return $handlerNamespace;
    }
}
