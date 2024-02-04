<?php

declare(strict_types=1);

namespace App\Filament\Resources\Admin\User;

use Filament\Forms;
use Filament\Tables;
use App\Queries\Search;
use Filament\Forms\Form;
use App\Models\Role\Role;
use App\Models\User\User;
use App\Queries\QueryBus;
use Filament\Tables\Table;
use App\Queries\SearchFactory;
use App\Filters\Role\RoleFilter;
use App\Filters\User\UserFilter;
use Illuminate\Support\Facades\App;
use App\Filament\Resources\Resource;
use Illuminate\Support\Facades\Lang;
use App\Models\Permission\Permission;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\HasGlobalSearch;
use App\Filament\Resources\Admin\User\Pages;
use App\Filament\Resources\GlobalSearchInterface;
use App\Queries\User\GetByFilter\GetByFilterQuery;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Admin\Role\RelationManagers;
use App\Filament\Resources\Admin\User\Pages\ManageUsers;
use Illuminate\Contracts\Container\BindingResolutionException;

final class UserResource extends Resource implements GlobalSearchInterface
{
    use HasGlobalSearch;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-s-users';

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
