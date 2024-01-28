<?php

declare(strict_types=1);

namespace App\Models\Role;

use App\Scopes\Role\HasRoleScopes;
use Database\Factories\Role\RoleFactory;
use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Role\Role
 *
 * @property int $id
 * @property \App\ValueObjects\Role\Name $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\User> $users
 * @property-read int|null $users_count
* @method static \Database\Factories\Role\RoleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterExcept(?array $except = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterOrderBy(?\App\Queries\OrderBy $orderby = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterOrderBySearch(?\App\Queries\Search $search = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterPaginate(?\App\Queries\Paginate $paginate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearch(?\App\Queries\Search $search = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterSearchAttributes(?\App\Queries\Search $search = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role withAllRelations()
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterGet(\App\Queries\Get $get)
 * @method static \Illuminate\Database\Eloquent\Builder|Role filterResult(\App\Queries\Paginate|\App\Queries\Get|null $result = null)
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
        'name' => \App\Casts\Role\NameCast::class,
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
}
