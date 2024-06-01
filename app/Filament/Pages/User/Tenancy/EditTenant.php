<?php

declare(strict_types=1);

namespace App\Filament\Pages\User\Tenancy;

use Filament\Forms\Form;
use App\Commands\CommandBusInterface;
use App\Models\Tenant\Tenant;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Commands\Tenant\Edit\EditCommand;
use Filament\Pages\Tenancy\EditTenantProfile;
use App\Filament\Actions\User\Tenancy\DeleteTenant;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\UsersRelationManager;

/**
 * @property-read Tenant $tenant
 */
class EditTenant extends EditTenantProfile
{
    protected static ?string $slug = 'edit';

    private CommandBusInterface $commandBus;

    public function boot(CommandBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    public static function getLabel(): string
    {
        /** @var Tenant */
        $tenant = Filament::getTenant();

        return Lang::get('tenant.pages.edit.title', [
            'name' => $tenant->name
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteTenant::make($this->tenant)
        ];
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

    /**
     * @param Tenant $record
     * @param array<string, mixed> $data
     * @return Tenant
     */
    protected function handleRecordUpdate(Model $record, array $data): Tenant
    {
        /** @var Tenant */
        $tenant = $this->commandBus->execute(new EditCommand(
            name: $data['name'],
            user: Auth::user(),
            tenant: $record,
            users: $record->users
        ));

        return $tenant;
    }

    protected function getSavedNotificationTitle(): string
    {
        return Lang::get('tenant.messages.edit.success', [
            'name' => $this->tenant->name
        ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class
        ];
    }
}
