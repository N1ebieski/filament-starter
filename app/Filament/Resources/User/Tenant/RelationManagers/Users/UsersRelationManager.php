<?php

declare(strict_types=1);

namespace App\Filament\Resources\User\Tenant\RelationManagers\Users;

use App\Queries\Order;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Models\User\User;
use Filament\Tables\Table;
use App\Models\Tenant\Tenant;
use App\Queries\SearchFactory;
use App\Queries\QueryBusInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Collection;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\AttachUserAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\DetachUserAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\DetachUsersAction;
use App\Filament\Resources\User\Tenant\RelationManagers\Users\Actions\EditPermissionsAction;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    private QueryBusInterface $queryBus;

    private SearchFactory $searchFactory;

    private User $user;

    public function boot(
        QueryBusInterface $queryBus,
        SearchFactory $searchFactory,
        User $user
    ): void {
        $this->queryBus = $queryBus;
        $this->searchFactory = $searchFactory;
        $this->user = $user;
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

    private function getSearch(?string $search): ?Search
    {
        return !is_null($search) && mb_strlen($search) > 2 ?
            $this->searchFactory->make($search, $this->user) : null;
    }

    public function table(Table $table): Table
    {
        /** @var Tenant */
        $tenant = $this->getOwnerRecord();

        return $table
            ->searchable(true)
            ->query(function () use ($tenant): Builder {
                return $this->queryBus->execute(new GetByFilterQuery(
                    search: $this->getSearch($this->getTableSearch()),
                    tenants: new Collection([$tenant])
                ));
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
