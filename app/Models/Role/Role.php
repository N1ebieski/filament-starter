<?php

declare(strict_types=1);

namespace App\Models\Role;

use App\Models\Shared\Attributes\AttributesInterface;
use App\Models\Shared\Attributes\HasAttributes;
use App\Models\Shared\Attributes\HasCamelCaseAttributes;
use App\Models\Shared\Data\DataInterface;
use App\Models\Shared\Searchable\SearchableInterface;
use App\QueryBuilders\Role\RoleQueryBuilder;
use App\ValueObjects\Role\Name\Name;
use Database\Factories\Role\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Override;
use Spatie\Permission\Models\Role as BaseRole;
use Spatie\Permission\PermissionRegistrar;

/**
 * @mixin RoleData
 * @mixin \Eloquent
 * @property int $id
 * @property \App\ValueObjects\Role\Name\Name $name
 *
 * @method static RoleQueryBuilder query()
 * @method static RoleFactory factory($count = null, $state = [])
 */
final class Role extends BaseRole implements AttributesInterface, DataInterface, SearchableInterface
{
    use HasAttributes;
    use HasFactory;
    use HasCamelCaseAttributes;

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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'tenant_id' => 'integer',
        'name' => Name::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public protected(set) array $selectAlways = ['id', 'tenant_id'];

    public protected(set) array $selectable = ['name', 'created_at', 'updated_at'];

    public protected(set) array $withable = ['permissions'];

    public protected(set) array $sortable = ['id', 'name', 'created_at', 'updated_at'];

    public protected(set) array $searchable = ['name'];

    public protected(set) array $searchableAttributes = ['id'];

    public RoleData $data { get => RoleData::from($this); }

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

    // Factories

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     */
    public function newEloquentBuilder($query): RoleQueryBuilder
    {
        /** @disregard */
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
