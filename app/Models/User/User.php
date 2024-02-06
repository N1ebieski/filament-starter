<?php

namespace App\Models\User;

use Filament\Panel;
use App\Models\Tenant\Tenant;
use Filament\Facades\Filament;
use Laravel\Sanctum\HasApiTokens;
use App\Scopes\User\HasUserScopes;
use App\ValueObjects\User\StatusEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Auth\VerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Extends\Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use HasRoles;
    use HasUserScopes;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable;

    // Configuration

    /**
     * @var string
     */
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The columns of the full text index
     */
    public array $searchable = ['name', 'email'];

    public array $searchableAttributes = ['id'];

    public function getTenants(Panel $panel): array | Collection
    {
        return $this->tenants;
    }

    // Overrides

    public function sendEmailVerificationNotification(): void
    {
        $notification = new VerifyEmail();
        $notification->url = Filament::getDefaultPanel()->getVerifyEmailUrl($this);

        $this->notify($notification);
    }

    // Attributes

    public function statusEmail(): Attribute
    {
        return new Attribute(fn (): StatusEmail => !is_null($this->email_verified_at) ?
            StatusEmail::Verified : StatusEmail::Unverified);
    }

    // Policies

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            \App\Providers\Filament\UserPanelProvider::ID => $panel->auth()->check(),
            \App\Providers\Filament\AdminPanelProvider::ID => $panel->auth()->user()?->can('admin.access'),
            \App\Providers\Filament\WebPanelProvider::ID => true,
            default => false
        };
    }

    /**
     * @param Tenant $tenant
     * @return bool
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $tenant->user->id == $this->id;
    }

    // Relations

    public function tenants(): MorphToMany
    {
        return $this->morphToMany(\App\Models\Tenant\Tenant::class, 'authenticatable', 'tenants_models');
    }
}
