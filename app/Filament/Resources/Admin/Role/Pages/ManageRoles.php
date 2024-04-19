<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Pages;

use App\Queries\Order;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Queries\QueryBus;
use Filament\Tables\Table;
use App\Queries\SearchFactory;
use App\Filament\Pages\HasMeta;
use App\Filters\Role\RoleFilter;
use App\View\Metas\MetaInterface;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Actions\BulkActionGroup;
use App\View\Metas\Admin\Role\IndexMetaFactory;
use App\Queries\Role\GetByFilter\GetByFilterQuery;
use App\Filament\Resources\Admin\Role\RoleResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\Admin\Role\Actions\EditRole;
use App\Filament\Resources\Admin\Role\Actions\CreateRole;
use App\Filament\Resources\Admin\Role\Actions\DeleteRole;
use App\Filament\Pages\MetaInterface as PageMetaInterface;
use App\Filament\Resources\Admin\Role\Actions\DeleteRoles;

final class ManageRoles extends ManageRecords implements PageMetaInterface
{
    use HasMeta;

    protected static string $resource = RoleResource::class;

    private Role $role;

    private SearchFactory $searchFactory;

    private QueryBus $queryBus;

    private IndexMetaFactory $metaFactory;

    public function boot(
        Role $role,
        QueryBus $queryBus,
        SearchFactory $searchFactory,
        IndexMetaFactory $metaFactory
    ): void {
        $this->role = $role;
        $this->queryBus = $queryBus;
        $this->searchFactory = $searchFactory;
        $this->metaFactory = $metaFactory;
    }

    public function getTitle(): string
    {
        return Lang::get('role.pages.index.title');
    }

    public static function getNavigationLabel(): string
    {
        return '';
    }

    public function getMeta(): MetaInterface
    {
        return $this->metaFactory->make($this->getPage());
    }

    private function getSearch(?string $search): ?Search
    {
        return !is_null($search) && mb_strlen($search) > 2 ?
            $this->searchFactory->make($search, $this->role) : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateRole::make()
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->query(function (): Builder {
                return $this->queryBus->execute(new GetByFilterQuery(
                    filters: new RoleFilter(
                        search: $this->getSearch($this->getTableSearch())
                    )
                ));
            })
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(query: function (Builder|Role $query, string $direction): Builder {
                        return $query->filterOrderBy(new OrderBy('id', Order::from($direction)));
                    }),

                TextColumn::make('name')
                    ->label(Lang::get('role.name.label'))
                    ->grow(),

                TextColumn::make('created_at')
                    ->label(Lang::get('default.created_at.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: function (Builder|Role $query, string $direction): Builder {
                        return $query->filterOrderBy(new OrderBy('created_at', Order::from($direction)));
                    }),

                TextColumn::make('updated_at')
                    ->label(Lang::get('default.updated_at.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: function (Builder|Role $query, string $direction): Builder {
                        return $query->filterOrderBy(new OrderBy('updated_at', Order::from($direction)));
                    }),
            ])
            ->actions([
                EditRole::make(),
                DeleteRole::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteRoles::make()
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(function (Builder|Role $query): Builder {
                return $query->filterOrderBy(new OrderBy('id', Order::Desc));
            });
    }
}
