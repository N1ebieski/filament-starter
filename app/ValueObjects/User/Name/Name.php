<?php

declare(strict_types=1);

namespace App\ValueObjects\User\Name;

use App\Support\ValueObject\ValueObject;
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
