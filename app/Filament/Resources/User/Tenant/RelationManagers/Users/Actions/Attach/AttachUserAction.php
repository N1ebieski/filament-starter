<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Attach;

use App\Commands\CommandBusInterface;
use App\Commands\User\Tenants\Attach\AttachCommand;
use App\Filament\Actions\Action;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\HasPermissions;
use App\Models\Permission\Permission;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Queries\QueryBusInterface;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rules\Exists;

final class AttachUserAction extends Action
{
    use HasPermissions;

    public function __construct(
        private readonly User $user,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly Permission $permission
    ) {}

    public static function make(Tenant $tenant): AttachAction
    {
        /** @var static */
        $static = App::make(self::class);

        return $static->makeAction($tenant);
    }

    public function makeAction(Tenant $tenant): AttachAction
    {
        return AttachAction::make()
            ->hidden(function (Guard $guard) use ($tenant): bool {
                /** @var User|null */
                $user = $guard->user();

                return ! $user?->can('tenantAttach', [$this->user::class, $tenant]);
            })
            ->icon('heroicon-o-plus-circle')
            ->modalHeading(Lang::string('tenant.pages.users.attach.title'))
            ->form(fn (AttachAction $action): array => [
                $action->recordTitle(fn (User $record): string => $record->name->value)
                    ->getRecordSelect()
                    ->label(Lang::string('user.name.label'))
                    ->hiddenLabel(false),

                Select::make('permissions')
                    ->label(Lang::string('user.permissions.label'))
                    ->options($this->getGroupedPermissions()->toArray())
                    ->searchable()
                    ->multiple()
                    ->exists(
                        $this->permission->getTable(),
                        'id',
                        fn (Exists $rule): Exists => $rule
                            ->where(fn (Builder $builder): Builder => $builder
                                ->where('name', 'like', 'tenant.%')
                            )
                    ),
            ])
            ->stickyModalFooter()
            ->closeModalByClickingAway(false)
            ->using(fn (array $data): bool => $this->commandBus->execute(AttachCommand::from([
                ...$data,
                'tenant' => $tenant,
                'user' => $this->user->find($data['recordId']),
            ])))
            ->successNotificationTitle(fn (User $record): string => Lang::string('tenant.messages.users.attach.success', [
                'name' => $record->name,
            ]));
    }
}
