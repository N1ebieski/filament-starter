<?php

namespace App\View\Components\Topbar\SpotlightButton;

use App\View\Components\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;

final class SpotlightButtonComponent extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function spotlightAction(): Action
    {
        return Action::make('spotlight')
            ->livewireClickHandlerEnabled(false)
            ->color('gray')
            ->icon('heroicon-m-magnifying-glass')
            ->iconButton();
    }

    public function render(): ViewContract
    {
        return View::make('components.topbar.spotlight-button.spotlight-button-component');
    }
}
