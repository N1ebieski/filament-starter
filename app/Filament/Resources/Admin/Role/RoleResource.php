<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role;

use Override;
use App\Models\Role\Role;
use App\Filament\Resources\Resource;
use Illuminate\Support\Facades\Lang;
use App\Support\Query\HasQueryBus;
use App\Filament\Resources\GlobalSearchInterface;
use App\Queries\Role\GetByFilter\GetByFilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\Admin\Role\Pages\Manage\ManageRolesPage;
use App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatchFactory;

final class RoleResource extends Resource implements GlobalSearchInterface
{
    use HasQueryBus;

    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-s-shield-check';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'roles';

    #[Override]
    public static function applyGlobalSearchAttributeConstraints(Builder $query, string $search): void
    {
        /** @var Builder */
        $baseQuery = static::getQueryBus()->execute(GetByFilterQuery::from([
            'search' => DatabaseMatchFactory::makeDatabaseMatch($search)
        ]));

        $query->setQuery($baseQuery->getQuery());
    }

    public static function getModelLabel(): string
    {
        return Lang::get('role.pages.index.title'); //@phpstan-ignore-line
    }

    public static function getPluralModelLabel(): string
    {
        return static::getModelLabel();
    }

    public static function getNavigationLabel(): string
    {
        return static::getModelLabel();
    }

    public static function getNavigationGroup(): ?string
    {
        return Lang::get('admin.groups.user'); //@phpstan-ignore-line
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRolesPage::route('/'),
        ];
    }
}
