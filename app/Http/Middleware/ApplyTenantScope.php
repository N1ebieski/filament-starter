<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Scopes\Tenant\TenantScope;

class ApplyTenantScope
{
    public function __construct(private readonly TenantScope $tenantScope)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        // Test::addGlobalScope($this->tenantScope);

        return $next($request);
    }
}
