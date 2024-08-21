<?php

declare(strict_types=1);

namespace App\ValueObjects\Tenant\Name;

use App\ValueObjects\ValueObject;
use Spatie\LaravelData\Attributes\Validation\Max;

final class Name extends ValueObject
{
    public function __construct(
        #[Max(255)]
        public readonly string $value
    ) {
        $this->validate(['value' => $value]);
    }
}
