<?php

namespace App\Filament\Pages\User\Tenancy;

use Filament\Forms\Form;
use App\Models\Tenant\Tenant;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
            ]);
    }

    protected function handleRegistration(array $data): Tenant
    {
        $team = Tenant::create($data);

        // $team->members()->attach(auth()->user());

        return $team;
    }
}
