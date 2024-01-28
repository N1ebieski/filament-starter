<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model implements HasCurrentTenantLabel
{
    use HasFactory;

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
        return 'Current team';
    }
}
