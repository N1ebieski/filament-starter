<?php

declare(strict_types=1);

namespace App\View\Components\Web\Topbar\LoginButton;

use App\View\Components\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Auth;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

final class LoginButtonComponent extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function loginAction(): Action
    {
        return Action::make('login')
            ->url(URL::route('filament.web.auth.login'))
            ->label(Lang::string('filament-panels::pages/auth/login.form.actions.authenticate.label'))
            ->visible(fn (): bool => Auth::guest())
            ->button()
            ->outlined();
    }

    public function render(): ViewContract
    {
        return View::make('components.web.topbar.login-button.login-button-component');
    }
}
