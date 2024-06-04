<?php

namespace App\Http\Middleware\Filament\Authenticate;

use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate as Middleware;

class AuthenticateHandler extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * @param Request $request
     * @return null|string
     */
    protected function redirectTo($request): ?string
    {
        return $request->expectsJson() ? null : Filament::getDefaultPanel()->getLoginUrl();
    }
}
