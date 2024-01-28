<?php

declare(strict_types=1);

namespace App\Support\Handler;

use Illuminate\Support\Str;

final class HandlerHelper
{
    public function __construct(private readonly Str $str)
    {
    }

    public function getNamespace(object $class): string
    {
        /** @var string */
        $classNamespace = get_class($class);
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
