<?php

declare(strict_types=1);

namespace App\Overrides\Jeffgreco13\FilamentBreezy\Livewire;

use App\Models\User\User;
use App\Overrides\Jeffgreco13\FilamentBreezy\BreezyCore;
use Filament\Facades\Filament;
use Filament\Forms\Components\Group;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as BasePersonalInfo;
use Override;

/**
 * @property-read User $user
 * @property-read \Filament\Forms\Form $form
 */
final class PersonalInfo extends BasePersonalInfo
{
    #[Override]
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'name' => $this->user->name->value,
            'email' => $this->user->email->value,
        ]);
    }

    protected function getProfileFormSchema(): array
    {
        $groupFields = Group::make([
            $this->getNameComponent(),
            $this->getEmailComponent(),
        ])->columnSpan($this->hasAvatars ? 2 : 'full');

        /** @var BreezyCore $breezyCore */
        $breezyCore = Filament::getPlugin('filament-breezy');

        return ($this->hasAvatars)
            ? [$breezyCore->getAvatarUploadComponent(), $groupFields]
            : [$groupFields];
    }
}
