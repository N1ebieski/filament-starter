<?php

namespace App\Http\Middleware\Filament\EnsureEmailIsVerified;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;

final class EnsureEmailIsVerifiedMiddleware extends EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user()) {
            return $next($request);
        }

        return parent::handle($request, $next, $redirectToRoute);
    }
}
