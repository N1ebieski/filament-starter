<?php

declare(strict_types=1);

namespace App\Http\Middleware\Filament;

use App\Models\Tenant\Tenant;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\PermissionRegistrar;

class TenantPermissions
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, \Closure $next)
    {
        if (!empty(Auth::user())) {
            /** @var PermissionRegistrar */
            $permissionRegistrar = App::make(PermissionRegistrar::class);

            /** @var Tenant */
            $tenant = Filament::getTenant();

            $permissionRegistrar->setPermissionsTeamId($tenant->id);
        }

        return $next($request);
    }
}
