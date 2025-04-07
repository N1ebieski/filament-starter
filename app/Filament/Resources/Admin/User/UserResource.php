<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User;

use App\Filament\Resources\Admin\User\Pages\Manage\ManageUsersPage;
use App\Filament\Resources\GlobalSearchInterface;
use App\Filament\Resources\Resource;
use App\Models\User\User;
use App\Overrides\Illuminate\Support\Facades\Lang;
use App\Queries\Shared\SearchBy\Drivers\Scout\Scout;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use App\Support\Query\HasQueryBus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Override;

final class UserResource extends Resource implements GlobalSearchInterface
{
    use HasQueryBus;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $slug = 'users';

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
        return Lang::string('user.pages.index.title');
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
            'index' => ManageUsersPage::route('/'),
        ];
    }
}
