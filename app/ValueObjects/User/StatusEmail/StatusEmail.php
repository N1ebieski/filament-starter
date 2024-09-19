<?php

declare(strict_types=1);

namespace App\ValueObjects\User\StatusEmail;

use App\Support\Enum\Enum;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Facades\Lang;

enum StatusEmail: string implements HasLabel
{
    use Enum;

    case Verified = 'verified';

    case Unverified = 'unverified';

    public function getAsBool(): bool
    {
        return match ($this) {
            self::Verified => true,
            self::Unverified => false
        };
    }

    public function toggle(): self
    {
        return match ($this) {
            self::Verified => self::Unverified,
            self::Unverified => self::Verified
        };
    }

    public function getLabel(): string
    {
        //@phpstan-ignore-next-line
        return match ($this) {
            self::Verified => Lang::get('user.status_email.verified'),
            self::Unverified => Lang::get('user.status_email.unverified')
        };
    }
}
