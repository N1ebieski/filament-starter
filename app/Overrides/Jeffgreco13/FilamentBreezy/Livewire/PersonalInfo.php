<?php

declare(strict_types=1);

namespace App\Overrides\Jeffgreco13\FilamentBreezy\Livewire;

use App\Models\User\User;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as BasePersonalInfo;

/**
 * @property-read User $user
 * @property-read \Filament\Forms\Form $form
 */
final class PersonalInfo extends BasePersonalInfo
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'name' => $this->user->name->value,
            'email' => $this->user->email->value,
        ]);
    }
}
