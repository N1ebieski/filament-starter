<?php

namespace App\Livewire\Components\Topbar;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\View;
use App\Livewire\Components\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Illuminate\Contracts\View\View as ViewContract;

final class SpotlightButton extends Component implements HasForms, HasActions
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
        return View::make('livewire.topbar.spotlight-button');
    }
}
