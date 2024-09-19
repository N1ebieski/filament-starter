<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users;

use App\Filament\Resources\HasTablePaginate;
use App\Filament\Resources\HasTableSearch;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Attach\AttachUserAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Detach\DetachUserAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\DetachMany\DetachUsersAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\EditPermissions\EditPermissionsAction;
use App\Models\Tenant\Tenant;
use App\Models\User\User;
use App\Queries\Order;
use App\Queries\OrderBy;
use App\Queries\QueryBusInterface;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class UsersRelationManager extends RelationManager
{
    use HasTablePaginate;
    use HasTableSearch;

    protected static string $relationship = 'users';

    private QueryBusInterface $queryBus;

    public function boot(
        QueryBusInterface $queryBus,
    ): void {
        $this->queryBus = $queryBus;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        /** @var User|null */
        $user = Auth::user();

        return $user?->can('usersViewAny', $ownerRecord) ?? false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return Lang::get('tenant.pages.users.index.title');
    }

    public function table(Table $table): Table
    {
        /** @var Tenant */
        $tenant = $this->getOwnerRecord();

        return $table
            ->searchable(true)
            ->query(function () use ($tenant): Builder {
                return $this->queryBus->execute(GetByFilterQuery::from([
                    'select' => ['id', 'name'],
                    'tenants' => new Collection([$tenant]),
                ]));
            })
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(Lang::get('user.name.label'))
                    ->grow(),
            ])
            ->headerActions([
                AttachUserAction::make($tenant),
            ])
            ->actions([
                EditPermissionsAction::make($tenant),
                DetachUserAction::make($tenant),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachUsersAction::make($tenant),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(function (Builder|User $query): Builder {
                return $query->filterOrderBy(new OrderBy('id', Order::Desc));
            });
    }
}
