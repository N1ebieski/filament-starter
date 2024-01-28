<?php

declare(strict_types=1);

namespace App\Filament\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

final class VerifyEmail
{
    public function handle(Request $request, Closure $next): mixed
    {
        $myProfileRouteName = MyProfilePage::getRouteName(panel: 'user');

        if ($request->user() && !$request->user()->hasVerifiedEmail() && !$request->routeIs($myProfileRouteName)) {
            return $request->expectsJson() ?
                App::abort(
                    HttpResponse::HTTP_FORBIDDEN,
                    'Your email address is not verified'
                )
                : Response::redirectTo($this->redirectTo($request));
        }

        return $next($request);
    }

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : Filament::getDefaultPanel()->getEmailVerificationPromptUrl();
    }
}
