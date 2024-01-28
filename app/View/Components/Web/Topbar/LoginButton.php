<?php

declare(strict_types=1);

namespace App\View\Components\Web\Topbar;

use Filament\Actions\Action;
use App\View\Components\Component;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Illuminate\Contracts\View\View as ViewContract;

final class LoginButton extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function loginAction(): Action
    {
        return Action::make('login')
            ->url(URL::route('filament.web.auth.login'))
            ->label(Lang::get('filament-panels::pages/auth/login.form.actions.authenticate.label'))
            ->visible(fn (): bool => !Auth::check())
            ->button()
            ->outlined();
    }

    public function render(): ViewContract
    {
        return View::make('components.web.topbar.login-button');
    }
}
