<?php

declare(strict_types=1);

namespace App\Http\Middleware\Filament\VerifyEmail;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use App\Http\Middleware\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

final class VerifyEmailMiddleware extends Middleware
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
                : Response::redirectTo($this->redirectTo());
        }

        return $next($request);
    }

    protected function redirectTo(): string
    {
        /** @var string */
        return Filament::getDefaultPanel()->getEmailVerificationPromptUrl();
    }
}
