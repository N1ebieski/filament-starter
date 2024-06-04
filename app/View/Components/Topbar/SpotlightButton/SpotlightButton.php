<?php

namespace App\View\Components\Topbar\SpotlightButton;

use Filament\Actions\Action;
use App\View\Components\Component;
use Illuminate\Support\Facades\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Illuminate\Contracts\View\View as ViewContract;

final class SpotlightButton extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function spotlightAction()
    {
        return Action::make('spotlight')
            ->livewireClickHandlerEnabled(false)
            ->color('gray')
            ->icon('heroicon-m-magnifying-glass')
            ->iconButton();
    }

    public function render(): ViewContract
    {
        return View::make('components.topbar.spotlight-button.spotlight-button');
    }
}
