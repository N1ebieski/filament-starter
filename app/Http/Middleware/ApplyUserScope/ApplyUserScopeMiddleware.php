<?php

declare(strict_types=1);

namespace App\Http\Middleware\ApplyUserScope;

use Closure;
use Illuminate\Http\Request;
use App\Scopes\User\UserScope;
use App\Http\Middleware\Middleware;

class ApplyUserScopeMiddleware extends Middleware
{
    //@phpstan-ignore-next-line
    public function __construct(private readonly UserScope $userScope)
    {
    }

    public function handle(Request $request, Closure $next): mixed
    {
        // Test::addGlobalScope($this->userScope);

        return $next($request);
    }
}
