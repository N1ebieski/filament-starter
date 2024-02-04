<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User;

use App\Models\User\User;
use App\Filters\User\UserFilter;
use App\Filament\Resources\Resource;
use Illuminate\Support\Facades\Lang;
use App\Filament\Resources\HasGlobalSearch;
use App\Filament\Resources\GlobalSearchInterface;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\Admin\User\Pages\ManageUsers;

final class UserResource extends Resource implements GlobalSearchInterface
{
    use HasGlobalSearch;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'users';

    public static function applyGlobalSearchAttributeConstraints(Builder $query, string $search): void
    {
        /** @var Builder */
        $baseQuery = static::getQueryBus()->execute(new GetByFilterQuery(
            filters: new UserFilter(
                search: static::getSearch($search)
            )
        ));

        $query->setQuery($baseQuery->getQuery());
    }

    public static function getModelLabel(): string
    {
        return Lang::get('user.pages.index.title');
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
            'index' => ManageUsers::route('/'),
        ];
    }
}
