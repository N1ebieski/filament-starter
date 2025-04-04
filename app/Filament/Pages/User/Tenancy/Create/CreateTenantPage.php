<?php

declare(strict_types=1);

namespace App\Filament\Pages\User\Tenancy\Create;

use App\Commands\CommandBusInterface;
use App\Commands\Tenant\Create\CreateCommand;
use App\Models\Tenant\Tenant;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public static function canView(): bool
    {
        return Gate::allows('userCreate', Tenant::class);
    }

    public static function canAccess(): bool
    {
        return Gate::allows('userCreate', Tenant::class);
    }

    public static function getLabel(): string
    {
        return Lang::string('tenant.pages.create.title');
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
            ->title(Lang::string('tenant.messages.create.success', [
                'name' => $this->tenant->name->value,
            ]))
            ->success()
            ->send();
    }
}
