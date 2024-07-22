<?php

declare(strict_types=1);

namespace App\Filament\Pages\User\Tenancy\Edit;

use Filament\Forms\Form;
use App\Models\Tenant\Tenant;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Commands\CommandBusInterface;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Commands\Tenant\Edit\EditCommand;
use Filament\Pages\Tenancy\EditTenantProfile;
use App\Filament\Actions\User\Tenancy\Delete\DeleteTenantAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\UsersRelationManager;

/**
 * @property-read Tenant $tenant
 */
class EditTenantPage extends EditTenantProfile
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
            DeleteTenantAction::make($this->tenant)
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
        $data['tenant'] = $record;

        /** @var Tenant */
        $tenant = $this->commandBus->execute(EditCommand::from($data));

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
