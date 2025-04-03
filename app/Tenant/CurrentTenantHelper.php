<?php

declare(strict_types=1);

namespace App\Tenant;

use App\Models\Tenant\Tenant;
use Filament\FilamentManager;
use Illuminate\Http\Request;

final readonly class CurrentTenantHelper
{
    public function __construct(
        private FilamentManager $filamentManager,
        private Request $request
    ) {}

    public function getTenant(): Tenant
    {
        if ($this->filamentManager->getCurrentPanel() instanceof \Filament\Panel) {
            /** @var Tenant */
            return $this->filamentManager->getTenant();
        }

        /** @var Tenant */
        return $this->request->route('tenant');
    }
}
