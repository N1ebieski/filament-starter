<?php

declare(strict_types=1);

namespace App\ValueObjects\User\StatusEmail;

use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Support\Enum\Enum;
use App\Support\Enum\EnumInterface;
use Filament\Support\Contracts\HasLabel;

enum StatusEmail: string implements EnumInterface, HasLabel
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
        return match ($this) {
            self::Verified => Lang::string('user.status_email.verified'),
            self::Unverified => Lang::string('user.status_email.unverified')
        };
    }
}
