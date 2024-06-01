<?php

declare(strict_types=1);

namespace App\Filament\Pages\Web;

use Override;
use Illuminate\Support\Facades\Auth;
use App\Spotlight\SpotlightInterface;
use App\Http\Middleware\Filament\Authenticate;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

final class MyProfile extends MyProfilePage implements SpotlightInterface
{
    /**
     * @var string | array<string>
     */
    protected static string | array $routeMiddleware = [
        Authenticate::class
    ];

    #[Override]
    public static function shouldRegisterSpotlight(): bool
    {
        return Auth::check();
    }
}
