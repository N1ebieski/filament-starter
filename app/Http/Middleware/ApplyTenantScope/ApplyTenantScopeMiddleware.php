<?php

declare(strict_types=1);

namespace App\Http\Middleware\ApplyTenantScope;

use App\GlobalScopes\Tenant\TenantScope;
use App\Http\Middleware\Middleware;
use Closure;
use Illuminate\Http\Request;

class ApplyTenantScopeMiddleware extends Middleware
{
    //@phpstan-ignore-next-line
    public function __construct(private readonly TenantScope $tenantScope) {}

    public function handle(Request $request, Closure $next): mixed
    {
        // Test::addGlobalScope($this->tenantScope);

        return $next($request);
    }
}
