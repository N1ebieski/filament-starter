<?php

declare(strict_types=1);

namespace App\Filament\Pages\Web\Dashboard;

use App\Spotlight\SpotlightInterface;
use Filament\Pages\Dashboard as BaseDashboard;

final class DashboardPage extends BaseDashboard implements SpotlightInterface
{
    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }
}
