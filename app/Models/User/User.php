<?php

namespace App\Models\User;

use Filament\Panel;
use App\Models\Tenant\Tenant;
use Filament\Facades\Filament;
use Laravel\Sanctum\HasApiTokens;
use App\Scopes\User\HasUserScopes;
use App\ValueObjects\User\Name\Name;
use App\ValueObjects\User\Email\Email;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use App\Casts\ValueObject\ValueObjectCast;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Auth\VerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Overrides\Spatie\Permission\Traits\HasRoles;
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
    use PivotEventTrait;
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
        'name' => ValueObjectCast::class . ':' . Name::class,
        'email' => ValueObjectCast::class . ':' . Email::class,
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

    /**
     * The channels the user receives notification broadcasts on.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return "Notification.{$this->getKey()}";
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
            \App\Providers\Filament\UserPanel\UserPanelServiceProvider::ID => $panel->auth()->check(),
            \App\Providers\Filament\AdminPanel\AdminPanelServiceProvider::ID => (function (Panel $panel) {
                /** @var self|null */
                $user = $panel->auth()->user();

                return $user?->can('admin.access') ?? false;
            })($panel),
            \App\Providers\Filament\WebPanel\WebPanelServiceProvider::ID => true,
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

    public function ownedTenants(): HasMany
    {
        return $this->hasMany(\App\Models\Tenant\Tenant::class);
    }

    public function tenants(): MorphToMany
    {
        return $this->morphToMany(\App\Models\Tenant\Tenant::class, 'authenticatable', 'tenants_models');
    }
}
