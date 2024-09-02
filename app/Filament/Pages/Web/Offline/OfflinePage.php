<?php

namespace App\Filament\Pages\Web\Offline;

use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Lang;

class OfflinePage extends SimplePage
{
    protected static ?string $slug = 'offline';

    protected static string $view = 'filament.pages.web.offline.offline';

    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return Lang::get('offline.pages.offline.title');
    }
}
