<?php

namespace App\Filament\Pages\User;

use Filament\Pages\Page;

class Test extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.user.test';

    public static function canAccess(): bool
    {
        return auth()->user()->can('tenant.test');
    }
}
