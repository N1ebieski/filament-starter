<?php

declare(strict_types=1);

namespace App\Support\Attributes;

use Attribute;

#[Attribute()]
final class Handler
{
    public function __construct(public readonly string $class)
    {
        if (!class_exists($class)) {
            throw new ClassNotExistsException(
                "The class {$class} does not exist"
            );
        }
    }
}
