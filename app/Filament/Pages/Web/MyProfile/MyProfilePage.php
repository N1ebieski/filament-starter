<?php

declare(strict_types=1);

namespace App\Filament\Pages\Web\MyProfile;

use App\Http\Middleware\Filament\Authenticate\AuthenticateMiddleware;
use App\Spotlight\SpotlightInterface;
use Illuminate\Support\Facades\Auth;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage as BaseMyProfilePage;
use Override;

final class MyProfilePage extends BaseMyProfilePage implements SpotlightInterface
{
    /**
     * @var string | array<string>
     */
    protected static string|array $routeMiddleware = [
        AuthenticateMiddleware::class,
    ];

    protected static ?string $navigationIcon = 'heroicon-c-user-circle';

    #[Override]
    public static function shouldRegisterSpotlight(): bool
    {
        return Auth::check();
    }
}
