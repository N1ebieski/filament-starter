<?php

declare(strict_types=1);

namespace App\Tenant;

use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use Filament\FilamentManager;

final class CurrentTenantFactory
{
    public function __construct(
        private readonly FilamentManager $filamentManager,
        private readonly Request $request
    ) {
    }

    public function getTenant(): Tenant
    {
        if ($this->filamentManager->getCurrentPanel()) {
            /** @var Tenant */
            return $this->filamentManager->getTenant();
        }

        /** @var Tenant */
        return $this->request->route('tenant');
    }
}
