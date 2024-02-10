<?php

declare(strict_types=1);

namespace App\Observers\Tenant;

use App\Models\Tenant\Tenant;

class TenantObserver
{
    public function deleted(Tenant $tenant): void
    {
        $tenant->users()->detach();
    }
}
