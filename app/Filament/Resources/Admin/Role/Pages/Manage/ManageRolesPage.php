<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role\Pages\Manage;

use App\Filament\Pages\HasMeta;
use App\Filament\Pages\MetaInterface as PageMetaInterface;
use App\Filament\Resources\Admin\Role\Actions\Create\CreateRoleAction;
use App\Filament\Resources\Admin\Role\Actions\Delete\DeleteRoleAction;
use App\Filament\Resources\Admin\Role\Actions\DeleteMany\DeleteRolesAction;
use App\Filament\Resources\Admin\Role\Actions\Edit\EditRoleAction;
use App\Filament\Resources\Admin\Role\RoleResource;
use App\Filament\Resources\HasTablePaginate;
use App\Filament\Resources\HasTableSearch;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Queries\QueryBusInterface;
use App\Queries\Role\GetByFilter\GetByFilterQuery;
use App\Queries\Shared\OrderBy\Order;
use App\Queries\Shared\OrderBy\OrderBy;
use App\QueryBuilders\Role\RoleQueryBuilder;
use App\View\Metas\Admin\Role\Index\IndexMetaFactory;
use App\View\Metas\MetaInterface;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;

final class ManageRolesPage extends ManageRecords implements PageMetaInterface
{
    use HasMeta;
    use HasTablePaginate;
    use HasTableSearch;

    protected static string $resource = RoleResource::class;

    private QueryBusInterface $queryBus;

    private IndexMetaFactory $metaFactory;

    public function boot(
        QueryBusInterface $queryBus,
        IndexMetaFactory $metaFactory
    ): void {
        $this->queryBus = $queryBus;
        $this->metaFactory = $metaFactory;
    }

    public function getTitle(): string
    {
        return Lang::string('role.pages.index.title');
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
            CreateRoleAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->searchable(true)
            ->query(fn (): Builder => $this->queryBus->execute(GetByFilterQuery::from([
                'select' => ['id', 'name', 'created_at', 'updated_at'],
            ])))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(query: fn (RoleQueryBuilder $query, string $direction): Builder => $query
                        ->filterOrderBy(new OrderBy('id', Order::from($direction)))
                    ),

                TextColumn::make('name')
                    ->label(Lang::string('role.name.label'))
                    ->grow(),

                TextColumn::make('created_at')
                    ->label(Lang::string('default.created_at.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: fn (RoleQueryBuilder $query, string $direction): Builder => $query
                        ->filterOrderBy(new OrderBy('created_at', Order::from($direction)))
                    ),

                TextColumn::make('updated_at')
                    ->label(Lang::string('default.updated_at.label'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(query: fn (RoleQueryBuilder $query, string $direction): Builder => $query
                        ->filterOrderBy(new OrderBy('updated_at', Order::from($direction)))
                    ),
            ])
            ->actions([
                EditRoleAction::make(),
                DeleteRoleAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteRolesAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null)
            ->defaultSort(fn (RoleQueryBuilder $query): Builder => $query->filterOrderBy(new OrderBy('id', Order::Desc)));
    }
}
