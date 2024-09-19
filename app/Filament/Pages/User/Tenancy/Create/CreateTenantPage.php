<?php

declare(strict_types=1);

namespace App\Filament\Pages\User\Tenancy\Create;

use App\Commands\CommandBusInterface;
use App\Commands\Tenant\Create\CreateCommand;
use App\Models\Tenant\Tenant;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

/**
 * @property-read Tenant $tenant
 */
class CreateTenantPage extends RegisterTenant
{
    protected static ?string $slug = 'tenants/create';

    private CommandBusInterface $commandBus;

    public function boot(CommandBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    public static function getLabel(): string
    {
        //@phpstan-ignore-next-line
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
        $tenant = $this->commandBus->execute(CreateCommand::from([
            ...$data,
            'user' => Auth::user(),
        ]));

        return $tenant;
    }

    public function afterRegister(): void
    {
        Notification::make()
            ->title(Lang::get('tenant.messages.create.success', [ //@phpstan-ignore-line
                'name' => $this->tenant->name->value,
            ]))
            ->success()
            ->send();
    }
}
