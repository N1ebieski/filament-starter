<?php

declare(strict_types=1);

namespace App\Http\Middleware\ApplyUserScope;

use App\GlobalScopes\User\UserScope;
use App\Http\Middleware\Middleware;
use Closure;
use Illuminate\Http\Request;

class ApplyUserScopeMiddleware extends Middleware
{
    //@phpstan-ignore-next-line
    public function __construct(private readonly UserScope $userScope) {}

    public function handle(Request $request, Closure $next): mixed
    {
        // Test::addGlobalScope($this->userScope);

        return $next($request);
    }
}
