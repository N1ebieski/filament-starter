<?php

namespace App\Models\Tenant;

use App\Models\Model;
use Illuminate\Support\Facades\Lang;
use App\ValueObjects\Tenant\Name\Name;
use App\Casts\ValueObject\ValueObjectCast;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 * @mixin \Eloquent
 */
class Tenant extends Model implements HasCurrentTenantLabel
{
    use HasFactory;
    use PivotEventTrait;

    // Configuration

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'name' => ValueObjectCast::class . ':' . Name::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected array $selectable = ['id', 'name', 'created_at', 'updated_at'];

    protected array $withable = ['users'];

    protected array $sortable = ['id', 'name', 'created_at', 'updated_at'];

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
