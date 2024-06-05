<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Pages;

use Override;
use App\Queries\Order;
use App\Queries\Search;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use Filament\Tables\Table;
use App\Queries\SearchFactory;
use App\Filament\Pages\HasMeta;
use App\View\Metas\MetaInterface;
use App\Queries\QueryBusInterface;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Actions\BulkActionGroup;
use App\Queries\Role\GetByFilter\GetByFilterQuery;
use App\Filament\Resources\Admin\Role\RoleResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\View\Metas\Admin\Role\Index\IndexMetaFactory;
use App\Filament\Pages\MetaInterface as PageMetaInterface;
use App\Filament\Resources\Admin\Role\Actions\EditRoleAction;
use App\Filament\Resources\Admin\Role\Actions\CreateRoleAction;
use App\Filament\Resources\Admin\Role\Actions\DeleteRoleAction;
use App\Filament\Resources\Admin\Role\Actions\DeleteRolesAction;

final class ManageRolesPage extends ManageRecords implements PageMetaInterface
{
    use HasMeta;

    protected static string $resource = RoleResource::class;

    private Role $role;

    private SearchFactory $searchFactory;

    private QueryBusInterface $queryBus;

    private IndexMetaFactory $metaFactory;

    public function boot(
        Role $role,
        QueryBusInterface $queryBus,
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

    #[Override]
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
            CreateRoleAction::make()
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->query(function (): Builder {
                return $this->queryBus->execute(new GetByFilterQuery(
                    search: $this->getSearch($this->getTableSearch())
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
                EditRoleAction::make(),
                DeleteRoleAction::make()
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteRolesAction::make()
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(function (Builder|Role $query): Builder {
                return $query->filterOrderBy(new OrderBy('id', Order::Desc));
            });
    }
}
