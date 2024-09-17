<?php

namespace App\Filament\Pages\Web\Test;

use App\Filament\Pages\Page;
use App\Support\PWA\PWACacheInterface;

class TestPage extends Page implements PWACacheInterface
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Test';

    protected static ?string $slug = 'test';

    protected static string $view = 'filament.pages.web.test.test';

    public static function getUrlForPWA(): string
    {
        return self::getUrl(isAbsolute: false);
    }

    public static function shouldRegisterSpotlight(): bool
    {
        return true;
    }
}
