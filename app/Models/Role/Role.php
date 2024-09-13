<?php

declare(strict_types=1);

namespace App\Models\Role;

use Override;
use App\Models\HasAttributes;
use App\Scopes\Role\HasRoleScopes;
use Illuminate\Support\Facades\App;
use App\ValueObjects\Role\Name\Name;
use App\Models\HasAttributesInterface;
use Illuminate\Support\Facades\Config;
use Database\Factories\Role\RoleFactory;
use App\Casts\ValueObject\ValueObjectCast;
use App\Models\HasDatabaseMatchSearchable;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterGet(\App\Queries\Shared\Result\Drivers\Get\Get $get)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterIgnore(?array $ignore)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterOrderBy(?\App\Queries\OrderBy $orderBy)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterOrderByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Shared\Result\Drivers\Paginate\Paginate $paginate)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterResult(?\App\Queries\Shared\Result\ResultInterface $result)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearchAttributesByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearchBy(?\App\Queries\Shared\SearchBy\SearchByInterface $searchBy)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearchByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearchByScout(\App\Queries\Shared\SearchBy\Drivers\Scout\Scout $scout)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSelect(?array $select)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterWith(?array $with)
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role withoutPermission($permissions)
 * @mixin \Eloquent
 */
final class Role extends BaseRole implements HasAttributesInterface
{
    use HasFactory;
    use HasAttributes;
    use HasRoleScopes;
    use HasDatabaseMatchSearchable;

    // Configuration

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'tenant_id' => 'integer',
        'name' => ValueObjectCast::class . ':' . Name::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $selectable = ['id', 'name', 'created_at', 'updated_at'];

    protected array $withable = ['permissions'];

    protected array $sortable = ['id', 'name', 'created_at', 'updated_at'];

    protected array $searchable = ['name'];

    protected array $searchableAttributes = ['id'];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): RoleFactory
    {
        return RoleFactory::new();
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
            getModelForGuard($this->attributes['guard_name'] ?? Config::get('auth.defaults.guard')), //@phpstan-ignore-line
            'authenticatable',
            Config::get('permission.table_names.model_has_roles'),
            $permissionRegistrar->pivotRole,
            Config::get('permission.column_names.model_morph_key')
        );
    }
}
