<?php

declare(strict_types=1);

namespace App\Filament\Pages\User\Tenancy\Edit;

use App\Commands\CommandBusInterface;
use App\Commands\Tenant\Edit\EditCommand;
use App\Filament\Actions\User\Tenancy\Delete\DeleteTenantAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\UsersRelationManager;
use App\Models\Tenant\Tenant;
use App\Overrides\Illuminate\Support\Facades\Lang;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Resources\Pages\Concerns\HasRelationManagers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Override;

/**
 * @property-read Tenant $tenant
 */
class EditTenantPage extends EditTenantProfile
{
    use HasRelationManagers;

    protected static ?string $slug = 'edit';

    private CommandBusInterface $commandBus;

    public function boot(CommandBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param  Tenant  $tenant
     */
    public static function canView(Model $tenant): bool
    {
        return Gate::allows('userUpdate', $tenant);
    }

    public static function canAccess(): bool
    {
        /** @var Tenant */
        $tenant = Filament::getTenant();

        return Gate::allows('userUpdate', $tenant);
    }

    protected function getAllRelationManagers(): array
    {
        return $this->getRelations();
    }

    protected function getRecord(): Tenant
    {
        return Filament::getTenant();
    }

    public static function getLabel(): string
    {
        /** @var Tenant */
        $tenant = Filament::getTenant();

        return Lang::string('tenant.pages.edit.title', [
            'name' => $tenant->name->value,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteTenantAction::make($this->tenant),
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

    #[Override]
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['name'] = $this->tenant->name->value;

        return $data;
    }

    /**
     * @param  Tenant  $record
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Tenant
    {
        /** @var Tenant */
        $tenant = $this->commandBus->execute(EditCommand::from([
            ...$data,
            'tenant' => $record,
        ]));

        return $tenant;
    }

    protected function getSavedNotificationTitle(): string
    {
        return Lang::string('tenant.messages.edit.success', [
            'name' => $this->tenant->name,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }
}
