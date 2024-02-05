<?php

namespace App\Filament\Resources\User\Tenant\RelationManagers\Morph;

use Filament\Forms;
use Filament\Tables;
use App\Queries\Order;
use App\Queries\Search;
use App\Queries\OrderBy;
use Filament\Forms\Form;
use App\Models\User\User;
use App\Queries\QueryBus;
use Filament\Tables\Table;
use App\Models\Tenant\Tenant;
use App\Queries\SearchFactory;
use App\Filters\User\UserFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DetachBulkAction;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\User\Tenant\RelationManagers\Morph\Actions\AttachMorph;

class MorphsRelationManager extends RelationManager
{
    protected static string $relationship = 'morphs';

    private QueryBus $queryBus;

    private SearchFactory $searchFactory;

    private User $user;

    public function boot(
        QueryBus $queryBus,
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

        return $ownerRecord->user->id === $user?->id;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return Lang::get('tenant.pages.morphs.index.title');
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
                    filters: new UserFilter(
                        search: $this->getSearch($this->getTableSearch()),
                        tenants: new Collection([$tenant])
                    )
                ));
            })
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(Lang::get('user.name.label'))
                    ->grow(),
            ])
            ->headerActions([
                AttachMorph::make($tenant),
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(function (Builder|User $query): Builder {
                return $query->filterOrderBy(new OrderBy('id', Order::Desc));
            });
    }
}
