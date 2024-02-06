<?php

namespace App\Models\Tenant;

use Illuminate\Support\Facades\Lang;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tenant extends Model implements HasCurrentTenantLabel
{
    use HasFactory;

    // Configuration

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name'
    ];

    public function getCurrentTenantLabel(): string
    {
        return Lang::get('tenant.current');
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
