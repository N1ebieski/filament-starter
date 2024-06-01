<?php

declare(strict_types=1);

namespace App\Filament\Pages\User\Tenancy;

use Filament\Forms\Form;
use App\Commands\CommandBusInterface;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Tenancy\RegisterTenant;
use App\Commands\Tenant\Create\CreateCommand;

class CreateTenant extends RegisterTenant
{
    protected static ?string $slug = 'tenants/create';

    private CommandBusInterface $commandBus;

    public function boot(CommandBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    public static function getLabel(): string
    {
        return Lang::get('tenant.pages.create.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->string()
                    ->minLength(3)
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Tenant
    {
        /** @var Tenant */
        $tenant = $this->commandBus->execute(new CreateCommand(
            name: $data['name'],
            user: Auth::user(),
        ));

        return $tenant;
    }

    public function afterRegister(): void
    {
        Notification::make()
            ->title(Lang::get('tenant.messages.create.success', [
                'name' => $this->tenant->name
            ]))
            ->success()
            ->send();
    }
}
