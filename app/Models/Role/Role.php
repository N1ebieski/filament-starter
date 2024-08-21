<?php

declare(strict_types=1);

namespace App\Models\Role;

use Override;
use App\Scopes\Role\HasRoleScopes;
use App\ValueObjects\Role\Name\Name;
use Database\Factories\Role\RoleFactory;
use App\Casts\ValueObject\ValueObjectCast;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Role\Role
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
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterExcept(?array $except)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterGet(\App\Queries\Get $get)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterOrderBy(?\App\Queries\OrderBy $orderby)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterOrderBySearch(?\App\Queries\Search\Search $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterPaginate(\App\Queries\Paginate $paginate)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterResult(\App\Queries\Paginate|\App\Queries\Get|null $result)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearch(?\App\Queries\Search\Search $search, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearchAttributes(?\App\Queries\Search\Search $search)
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role withAllRelations()
 * @method static \Illuminate\Database\Eloquent\Builder|Role withoutPermission($permissions)
 * @mixin \Eloquent
 */
final class Role extends BaseRole
{
    use HasFactory;
    use HasRoleScopes;

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

    /**
     * The columns of the full text index
     */
    public array $searchable = ['name'];

    public array $searchableAttributes = ['id'];

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
        return $this->morphedByMany(
            getModelForGuard($this->attributes['guard_name'] ?? config('auth.defaults.guard')),
            'authenticatable',
            config('permission.table_names.model_has_roles'),
            app(PermissionRegistrar::class)->pivotRole,
            config('permission.column_names.model_morph_key')
        );
    }
}
