<?php

namespace App\Filament\Pages\Web\Offline;

use Filament\Pages\SimplePage;
use Illuminate\Support\Facades\Lang;
use App\Support\PWA\PWACacheInterface;

class OfflinePage extends SimplePage implements PWACacheInterface
{
    protected static ?string $slug = 'offline';

    protected static string $view = 'filament.pages.web.offline.offline';

    protected static bool $shouldRegisterNavigation = false;

    public static function getUrlForPWA(): string
    {
        /** @var string */
        $slug = self::$slug;

        return "/{$slug}";
    }

    public static function shouldRegisterSpotlight(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        //@phpstan-ignore-next-line
        return Lang::get('offline.pages.offline.title');
    }
}
