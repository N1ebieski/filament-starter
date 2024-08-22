<?php

namespace App\Filament\Pages\User\Test;

use App\Filament\Pages\Shared\Page;

class TestPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.user.test.test';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('tenant.test') ?? false;
    }

    public static function shouldRegisterSpotlight(): bool
    {
        return self::canAccess();
    }
}
