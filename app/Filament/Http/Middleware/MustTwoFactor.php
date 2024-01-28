<?php

declare(strict_types=1);

namespace App\Filament\Http\Middleware;

use Closure;
use App\Models\User\User;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Http\Response as HttpResponse;

final class MustTwoFactor
{
    public function handle(Request $request, Closure $next): mixed
    {
        /** @var User */
        $user = $request->user();

        /** @var BreezyCore */
        $breezy = Filament::getPlugin('filament-breezy');

        if (
            $user && ($user->hasConfirmedTwoFactor() || $breezy->shouldForceTwoFactor())
            && !$user->hasValidTwoFactorSession()
        ) {
            return $request->expectsJson() ?
                App::abort(
                    HttpResponse::HTTP_FORBIDDEN,
                    'Your have to authenticate by 2FA.'
                )
                : Response::redirectTo($this->redirectTo($request));
        }

        return $next($request);
    }

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : Filament::getDefaultPanel()->route('auth.two-factor', [
            'next' => $request->getRequestUri()
        ]);
    }
}
