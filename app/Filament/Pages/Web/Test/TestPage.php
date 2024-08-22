<?php

namespace App\Filament\Pages\Web\Test;

use App\Filament\Pages\Shared\Page;

class TestPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = 'Test';

    protected static ?string $slug = 'test';

    protected static string $view = 'filament.pages.web.test.test';

    public static function shouldRegisterSpotlight(): bool
    {
        return true;
    }
}
