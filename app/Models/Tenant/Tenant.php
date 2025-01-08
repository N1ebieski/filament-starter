<?php

namespace App\Models\Tenant;

use App\Models\Model;
use App\Models\Shared\Attributes\AttributesInterface;
use App\ValueObjects\Tenant\Name\Name;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Lang;

/**
 * 
 *
 * @property int $id
 * @property \App\ValueObjects\Tenant\Name\Name $name
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Shared\Result\Drivers\Paginate\Paginate $paginate)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tenant query()
 * @mixin \Eloquent
 */
class Tenant extends Model implements AttributesInterface, HasCurrentTenantLabel
{
    use HasFactory;
    use PivotEventTrait;

    // Configuration

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'name' => Name::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public protected(set) array $selectAlways = ['id', 'user_id'];

    public protected(set) array $selectable = ['name', 'created_at', 'updated_at'];

    public protected(set) array $withable = ['user'];

    public protected(set) array $sortable = ['id', 'name', 'created_at', 'updated_at'];

    public function getCurrentTenantLabel(): string
    {
        return Lang::get('tenant.current'); //@phpstan-ignore-line
    }

    // Relations

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User\User::class);
    }

    public function users(): MorphToMany
    {
        return $this->morphedByMany(\App\Models\User\User::class, 'authenticatable', 'tenants_models', 'tenant_id');
    }
}
