<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\Role;

use App\Models\Role\Role;
use App\Filters\Role\RoleFilter;
use App\Filament\Resources\Resource;
use Illuminate\Support\Facades\Lang;
use App\Filament\Resources\HasGlobalSearch;
use App\Filament\Resources\GlobalSearchInterface;
use App\Queries\Role\GetByFilter\GetByFilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\Admin\Role\Pages\ManageRoles;

final class RoleResource extends Resource implements GlobalSearchInterface
{
    use HasGlobalSearch;

    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-s-shield-check';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'roles';

    public static function applyGlobalSearchAttributeConstraints(Builder $query, string $search): void
    {
        /** @var Builder */
        $baseQuery = static::getQueryBus()->execute(new GetByFilterQuery(
            filters: new RoleFilter(
                search: static::getSearch($search)
            )
        ));

        $query->setQuery($baseQuery->getQuery());
    }

    public static function getModelLabel(): string
    {
        return Lang::get('role.pages.index.title');
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
        return Lang::get('admin.groups.user');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRoles::route('/'),
        ];
    }
}
