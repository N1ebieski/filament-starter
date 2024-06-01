<?php

declare(strict_types=1);

namespace App\Filament\Pages\Web;

use App\Spotlight\SpotlightInterface;
use Filament\Pages\Dashboard as BaseDashboard;

final class Dashboard extends BaseDashboard implements SpotlightInterface
{
    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }
}
