<?php

declare(strict_types=1);

namespace App\Filament\Pages\Web\Dashboard;

use App\Spotlight\SpotlightInterface;
use App\Support\PWA\PWACacheInterface;
use Filament\Pages\Dashboard as BaseDashboard;

final class DashboardPage extends BaseDashboard implements SpotlightInterface, PWACacheInterface
{
    protected static bool $shouldRegisterNavigation = false;

    public static function getUrlForPWA(): string
    {
        return self::getUrl(isAbsolute: false);
    }

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }
}
