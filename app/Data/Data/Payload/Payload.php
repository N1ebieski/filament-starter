<?php

declare(strict_types=1);

namespace App\Data\Data\Payload;

final class Payload
{
    public function __construct(
        public array $payloads,
        public readonly string $classname
    ) {
    }
}
