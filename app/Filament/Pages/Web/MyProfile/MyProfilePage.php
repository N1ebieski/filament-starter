<?php

declare(strict_types=1);

namespace App\Filament\Pages\Web\MyProfile;

use Override;
use Illuminate\Support\Facades\Auth;
use App\Spotlight\SpotlightInterface;
use App\Http\Middleware\Filament\Authenticate\AuthenticateHandler;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage as BaseMyProfilePage;

final class MyProfilePage extends BaseMyProfilePage implements SpotlightInterface
{
    /**
     * @var string | array<string>
     */
    protected static string | array $routeMiddleware = [
        AuthenticateHandler::class
    ];

    #[Override]
    public static function shouldRegisterSpotlight(): bool
    {
        return Auth::check();
    }
}
