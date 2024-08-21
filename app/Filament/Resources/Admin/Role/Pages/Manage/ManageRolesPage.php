<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Pages\Manage;

use Override;
use App\Queries\Order;
use App\Queries\OrderBy;
use App\Models\Role\Role;
use App\Queries\Paginate;
use Filament\Tables\Table;
use App\Filament\Pages\HasMeta;
use App\View\Metas\MetaInterface;
use App\Queries\QueryBusInterface;
use Illuminate\Support\Facades\Lang;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Contracts\Pagination\Paginator;
use App\Queries\Role\GetByFilter\GetByFilterQuery;
use App\Filament\Resources\Admin\Role\RoleResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\View\Metas\Admin\Role\Index\IndexMetaFactory;
use App\Filament\Pages\MetaInterface as PageMetaInterface;
use App\Filament\Resources\Admin\Role\Actions\Edit\EditRoleAction;
use App\Filament\Resources\Admin\Role\Actions\Create\CreateRoleAction;
use App\Filament\Resources\Admin\Role\Actions\Delete\DeleteRoleAction;
use App\Filament\Resources\Admin\Role\Actions\DeleteMany\DeleteRolesAction;

final class ManageRolesPage extends ManageRecords implements PageMetaInterface
{
    use HasMeta;

    protected static string $resource = RoleResource::class;

    private Role $role;

    private QueryBusInterface $queryBus;

    private IndexMetaFactory $metaFactory;

    public function boot(
        Role $role,
        QueryBusInterface $queryBus,
        IndexMetaFactory $metaFactory
    ): void {
        $this->role = $role;
        $this->queryBus = $queryBus;
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
        return $this->metaFactory->makeMeta($this->getPage());
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
                return $this->queryBus->execute(GetByFilterQuery::from(search: $this->getTableSearch()));
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

    protected function paginateTableQuery(Builder|Role $query): Paginator
    {
        return $query->filterPaginate(new Paginate(
            perPage: (int)$this->getTableRecordsPerPage(),
            page: $this->getPage()
        ));
    }
}
