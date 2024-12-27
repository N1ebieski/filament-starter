<?php

namespace App\Models\Tenant;

use App\Casts\ValueObject\ValueObjectCast;
use App\Models\Model;
use App\ValueObjects\Tenant\Name\Name;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Lang;

/**
 * @mixin TenantData
 *
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Shared\Result\Drivers\Paginate\Paginate $paginate)
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tenant query()
 *
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
        'name' => ValueObjectCast::class.':'.Name::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public protected(set) array $selectAlways = ['id', 'user_id'];

    public protected(set) array $selectable = ['name', 'created_at', 'updated_at'];

    public protected(set) array $withable = ['user'];

    public protected(set) array $sortable = ['id', 'name', 'created_at', 'updated_at'];

    public TenantData $data {
        get => TenantData::from($this);
    }

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
