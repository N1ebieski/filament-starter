<?php

declare(strict_types=1);

namespace App\ValueObjects\User\Email;

use App\ValueObjects\ValueObject;
use Spatie\LaravelData\Attributes\Validation\Email as ValidationEmail;

final class Email extends ValueObject
{
    public function __construct(
        #[ValidationEmail()]
        public readonly string $value
    ) {
        $this->validate(['value' => $value]);
    }
}
