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
use App\Queries\QueryBusInterface;
use App\Queries\Shared\OrderBy\Order;
use App\Queries\Shared\OrderBy\OrderBy;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use App\QueryBuilders\User\UserQueryBuilder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
            ->query(fn(): Builder => $this->queryBus->execute(GetByFilterQuery::from([
                'select' => ['id', 'name'],
                'tenants' => new Collection([$tenant]),
            ])))
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
            ->defaultSort(fn(UserQueryBuilder $query): Builder => $query->filterOrderBy(new OrderBy('id', Order::Desc)));
    }
}
