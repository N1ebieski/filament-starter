<?php

declare(strict_types=1);

namespace App\Actions\Tenant\GetCurrent;

use App\Actions\Handler;
use App\Models\Tenant\Tenant;
use Filament\FilamentManager;
use Illuminate\Http\Request;

final class GetCurrentHandler extends Handler
{
    public function __construct(
        private readonly FilamentManager $filamentManager,
        private readonly Request $request
    ) {}

    public function handle(): Tenant
    {
        if ($this->filamentManager->getCurrentPanel() instanceof \Filament\Panel) {
            /** @var Tenant */
            return $this->filamentManager->getTenant();
        }

        /** @var Tenant */
        return $this->request->route('tenant');
    }
}
