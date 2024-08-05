<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users;

use App\Queries\Order;
use App\Queries\OrderBy;
use App\Models\User\User;
use App\Queries\Paginate;
use Filament\Tables\Table;
use App\Models\Tenant\Tenant;
use App\Queries\QueryBusInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Attach\AttachUserAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\Detach\DetachUserAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\DetachMany\DetachUsersAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\EditPermissions\EditPermissionsAction;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    private QueryBusInterface $queryBus;

    public function boot(
        QueryBusInterface $queryBus
    ): void {
        $this->queryBus = $queryBus;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        /** @var User|null */
        $user = Auth::user();

        return $user?->can('usersViewAny', $ownerRecord);
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
                    'search' => $this->getTableSearch(),
                    'tenants' => new Collection([$tenant])
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

    protected function paginateTableQuery(Builder|User $query): Paginator
    {
        return $query->filterPaginate(new Paginate($this->getTableRecordsPerPage(), $this->getPage()));
    }
}
