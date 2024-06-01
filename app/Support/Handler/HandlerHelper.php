<?php

declare(strict_types=1);

namespace App\Support\Handler;

use ReflectionClass;
use Illuminate\Support\Str;
use App\Support\Attributes\Handler;

final class HandlerHelper
{
    public function __construct(private readonly Str $str)
    {
    }

    /**
     * Finds and returns the Handler namespace for the specified object.
     *
     * Firstly checks if objact has a Handler attribute.
     *
     * If not, it looks for a Handler with an identical namespace.
     */
    public function getNamespace(object $class): string
    {
        /** @var string */
        $classNamespace = get_class($class);

        $reflectionClass = new ReflectionClass($classNamespace);

        $handlerAttributes = $reflectionClass->getAttributes(Handler::class);

        if (count($handlerAttributes) > 0) {
            /** @var Handler */
            $handlerAttribute = $handlerAttributes[0]->newInstance();

            return $handlerAttribute->class;
        }

        $classBasename = class_basename($class);

        $handlerNamespace = $this->str->beforeLast($classNamespace, '\\');

        $lastOccurance = $this->str->match('/([A-Z][^A-Z]*)$/', $classBasename);

        if (empty($lastOccurance)) {
            throw new IncorrectNameException("The class name: \"{$classBasename}\" is incorrect.");
        }

        $handlerName = $this->str->replaceLast($lastOccurance, 'Handler', $classBasename);

        $handlerNamespace = $handlerNamespace . '\\' . $handlerName;

        return $handlerNamespace;
    }
}
