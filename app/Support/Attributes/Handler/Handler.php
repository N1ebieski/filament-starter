<?php

declare(strict_types=1);

namespace App\Support\Attributes\Handler;

use Attribute;
use App\Support\Attributes\ClassNotExistException;
use App\Support\Attributes\Attribute as BaseAttribute;

#[Attribute()]
final class Handler extends BaseAttribute
{
    public function __construct(public readonly string $class)
    {
        if (!class_exists($class)) {
            throw new ClassNotExistException(
                "The class {$class} does not exist"
            );
        }
    }
}
