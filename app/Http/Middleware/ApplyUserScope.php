<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Scopes\User\UserScope;

class ApplyUserScope
{
    public function __construct(private readonly UserScope $userScope)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        // Test::addGlobalScope($this->userScope);

        return $next($request);
    }
}
