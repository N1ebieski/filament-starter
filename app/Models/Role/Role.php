<?php

declare(strict_types=1);

namespace App\Models\Role;

use App\Models\Shared\Attributes\AttributesInterface;
use App\Models\Shared\Attributes\HasAttributes;
use App\Models\Shared\Searchable\SearchableInterface;
use App\QueryBuilders\Role\RoleQueryBuilder;
use App\ValueObjects\Role\Name\Name;
use Database\Factories\Role\RoleFactory;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Override;
use Spatie\Permission\Models\Role as BaseRole;
use Spatie\Permission\PermissionRegistrar;

/**
 * 
 *
 * @property int $id
 * @property int|null $tenant_id
 * @property \App\ValueObjects\Role\Name\Name $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\Role\RoleFactory factory($count = null, $state = [])
 * @method static RoleQueryBuilder<static>|Role filterGet(\App\Queries\Shared\Result\Drivers\Get\Get $get)
 * @method static RoleQueryBuilder<static>|Role filterIgnore(?array $ignore)
 * @method static RoleQueryBuilder<static>|Role filterOrderBy(?\App\Queries\Shared\OrderBy\OrderBy $orderBy)
 * @method static RoleQueryBuilder<static>|Role filterOrderByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Shared\Result\Drivers\Paginate\Paginate $paginate)
 * @method static RoleQueryBuilder<static>|Role filterResult(?\App\Queries\Shared\Result\ResultInterface $result)
 * @method static RoleQueryBuilder<static>|Role filterSearchAttributesByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method static RoleQueryBuilder<static>|Role filterSearchBy(?\App\Queries\Shared\SearchBy\SearchByInterface $searchBy)
 * @method static RoleQueryBuilder<static>|Role filterSearchByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch, string $boolean = 'and')
 * @method static RoleQueryBuilder<static>|Role filterSearchByScout(\App\Queries\Shared\SearchBy\Drivers\Scout\Scout $scout)
 * @method static RoleQueryBuilder<static>|Role filterSelect(?array $select)
 * @method static RoleQueryBuilder<static>|Role filterWith(?array $with, bool $withAll = false)
 * @method static RoleQueryBuilder<static>|Role newModelQuery()
 * @method static RoleQueryBuilder<static>|Role newQuery()
 * @method static \App\QueryBuilders\Role\RoleQueryBuilder<static>|Role permission($permissions, $without = false)
 * @method static RoleQueryBuilder<static>|Role query()
 * @method static RoleQueryBuilder<static>|Role withAll()
 * @method static \App\QueryBuilders\Role\RoleQueryBuilder<static>|Role withoutPermission($permissions)
 * @mixin \Eloquent
 */
final class Role extends BaseRole implements AttributesInterface, SearchableInterface
{
    use HasAttributes;
    use HasFactory;

    // Configuration

    /**
     * @var string
     */
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'guard_name',
    ];

    public protected(set) array $selectAlways = ['id', 'tenant_id'];

    public protected(set) array $selectable = ['name', 'created_at', 'updated_at'];

    public protected(set) array $withable = ['permissions'];

    public protected(set) array $sortable = ['id', 'name', 'created_at', 'updated_at'];

    public protected(set) array $searchable = ['name'];

    public protected(set) array $searchableAttributes = ['id'];

    public protected(set) array $searchableRelations = [];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'tenant_id' => 'integer',
            'name' => Name::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relations

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    #[Override]
    public function users(): BelongsToMany
    {
        /** @var PermissionRegistrar */
        $permissionRegistrar = App::make(PermissionRegistrar::class);

        //@phpstan-ignore-next-line
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? Config::string('auth.defaults.guard')), //@phpstan-ignore-line
            'authenticatable',
            Config::string('permission.table_names.model_has_roles'),
            $permissionRegistrar->pivotRole,
            Config::string('permission.column_names.model_morph_key')
        );
    }

    // Factories

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder&ConnectionInterface  $query
     */
    public function newEloquentBuilder($query): RoleQueryBuilder
    {
        return new RoleQueryBuilder($query);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
    }
}
