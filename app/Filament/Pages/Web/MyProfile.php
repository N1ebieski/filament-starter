<?php

declare(strict_types=1);

namespace App\Filament\Pages\Web;

use App\Http\Middleware\Filament\Authenticate;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

final class MyProfile extends MyProfilePage
{
    /**
     * @var string | array<string>
     */
    protected static string | array $routeMiddleware = [
        Authenticate::class
    ];
}
