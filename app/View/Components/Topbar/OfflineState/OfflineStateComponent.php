<?php

namespace App\View\Components\Topbar\OfflineState;

use App\View\Components\Component;
use Illuminate\Support\Facades\View;
use Illuminate\Contracts\View\View as ViewContract;

class OfflineStateComponent extends Component
{
    public function render(): ViewContract
    {
        return View::make('components.topbar.offline-state.offline-state-component');
    }
}
