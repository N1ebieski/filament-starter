<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role;

use App\Filament\Resources\Admin\Role\Pages\Manage\ManageRolesPage;
use App\Filament\Resources\GlobalSearchInterface;
use App\Filament\Resources\Resource;
use App\Models\Role\Role;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Queries\Role\GetByFilter\GetByFilterQuery;
use App\Queries\Shared\SearchBy\Drivers\Scout\Scout;
use App\Support\Query\HasQueryBus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Override;

final class RoleResource extends Resource implements GlobalSearchInterface
{
    use HasQueryBus;

    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-s-shield-check';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'roles';

    public static function canAccess(): bool
    {
        return Gate::allows('adminViewAny', Role::class);
    }

    #[Override]
    public static function applyGlobalSearchAttributeConstraints(Builder $query, string $search): void
    {
        /** @var Builder */
        $baseQuery = self::getQueryBus()->execute(GetByFilterQuery::from([
            'search' => new Scout(query: $search, isOrderBy: true),
        ]));

        $query->setQuery($baseQuery->getQuery());
    }

    public static function getModelLabel(): string
    {
        return Lang::string('role.pages.index.title');
    }

    public static function getPluralModelLabel(): string
    {
        return self::getModelLabel();
    }

    public static function getNavigationLabel(): string
    {
        return self::getModelLabel();
    }

    public static function getNavigationGroup(): string
    {
        return Lang::string('admin.groups.user');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRolesPage::route('/'),
        ];
    }
}
