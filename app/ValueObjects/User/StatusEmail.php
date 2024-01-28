<?php

declare(strict_types=1);

namespace App\ValueObjects\User;

use App\Support\Enum\Enum;
use Illuminate\Support\Facades\Lang;
use Filament\Support\Contracts\HasLabel;

enum StatusEmail: string implements HasLabel
{
    use Enum;

    case VERIFIED = 'verified';

    case UNVERIFIED = 'unverified';

    public function getAsBool(): bool
    {
        return match ($this) {
            self::VERIFIED => true,
            self::UNVERIFIED => false
        };
    }

    public function toggle(): self
    {
        return match ($this) {
            self::VERIFIED => self::UNVERIFIED,
            self::UNVERIFIED => self::VERIFIED
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::VERIFIED => Lang::get('user.status_email.verified'),
            self::UNVERIFIED => Lang::get('user.status_email.unverified')
        };
    }
}
