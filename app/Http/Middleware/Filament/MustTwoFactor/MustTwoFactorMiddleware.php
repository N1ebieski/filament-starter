<?php

declare(strict_types=1);

namespace App\Http\Middleware\Filament\MustTwoFactor;

use App\Http\Middleware\Middleware;
use App\Models\User\User;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Jeffgreco13\FilamentBreezy\BreezyCore;

final class MustTwoFactorMiddleware extends Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var User */
        $user = $request->user();

        /** @var BreezyCore */
        $breezy = Filament::getPlugin('filament-breezy');

        if (
            $user && ($user->hasConfirmedTwoFactor() || $breezy->shouldForceTwoFactor())
            && ! $user->hasValidTwoFactorSession()
        ) {
            return $request->expectsJson() ?
                App::abort(
                    HttpResponse::HTTP_FORBIDDEN,
                    'You have to authenticate by 2FA.'
                )
                : Response::redirectTo($this->redirectTo($request));
        }

        return $next($request);
    }

    private function redirectTo(Request $request): string
    {
        return Filament::getDefaultPanel()->route('auth.two-factor', [
            'next' => $request->getRequestUri(),
        ]);
    }
}
