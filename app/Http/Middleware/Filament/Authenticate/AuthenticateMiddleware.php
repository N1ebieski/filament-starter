<?php

namespace App\Http\Middleware\Filament\Authenticate;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Http\Request;

class AuthenticateMiddleware extends Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     */
    protected function redirectTo($request): ?string
    {
        return $request->expectsJson() ? null : Filament::getDefaultPanel()->getLoginUrl();
    }
}
