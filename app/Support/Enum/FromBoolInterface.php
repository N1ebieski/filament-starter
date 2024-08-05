<?php

declare(strict_types=1);

namespace App\Support\Enum;

interface FromBoolInterface
{
    public static function fromBool(bool $value): self;
}
