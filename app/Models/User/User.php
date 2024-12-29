<?php

namespace App\Models\User;

use App\Casts\ValueObject\ValueObjectCast;
use App\Models\Shared\Attributes\AttributesInterface;
use App\Models\Shared\Attributes\HasAttributes;
use App\Models\Shared\Attributes\HasCamelCaseAttributes;
use App\Models\Shared\Data\DataInterface;
use App\Models\Shared\Searchable\SearchableInterface;
use App\Models\Tenant\Tenant;
use App\Overrides\Spatie\Permission\Traits\HasRoles;
use App\QueryBuilders\User\UserQueryBuilder;
use App\ValueObjects\User\Email\Email;
use App\ValueObjects\User\Name\Name;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Database\Factories\User\UserFactory;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin UserData
 * @method static UserQueryBuilder query()
 * @method static UserFactory factory($count = null, $state = [])
 */
class User extends Authenticatable implements FilamentUser, AttributesInterface, DataInterface, SearchableInterface, HasTenants, MustVerifyEmail
{
    use HasApiTokens;
    use HasAttributes;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use PivotEventTrait;
    use TwoFactorAuthenticatable;
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
        'email',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'name' => ValueObjectCast::class.':'.Name::class,
        'email' => ValueObjectCast::class.':'.Email::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public protected(set) array $selectAlways = ['id'];

    public protected(set) array $selectable = ['name', 'created_at', 'updated_at'];

    public protected(set) array $withable = ['roles', 'tenants', 'tenants.user'];

    public protected(set) array $sortable = ['id', 'name', 'created_at', 'updated_at'];

    public protected(set) array $searchable = ['name', 'email'];

    public protected(set) array $searchableAttributes = ['id'];

    public UserData $data { get => UserData::from($this); }

    public function getTenants(Panel $panel): array|Collection
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
        $notification = new VerifyEmail;
        $notification->url = Filament::getDefaultPanel()->getVerifyEmailUrl($this);

        $this->notify($notification);
    }

    // Attributes

    public function statusEmail(): Attribute
    {
        return new Attribute(fn (): StatusEmail => ! is_null($this->email_verified_at) ?
            StatusEmail::Verified : StatusEmail::Unverified);
    }

    // Policies

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            \App\Providers\Filament\UserPanel\UserPanelServiceProvider::ID => $panel->auth()->check(),

            \App\Providers\Filament\AdminPanel\AdminPanelServiceProvider::ID => value(function (Panel $panel) {
                /** @var self|null */
                $user = $panel->auth()->user();

                return $user?->can('admin.access') ?? false;
            }, $panel),

            \App\Providers\Filament\WebPanel\WebPanelServiceProvider::ID => true,

            default => false
        };
    }

    /**
     * @param  Tenant  $tenant
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $tenant->user?->id === $this->id;
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

    // Factories

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     */
    public function newEloquentBuilder($query): UserQueryBuilder
    {
        /** @disregard */
        return new UserQueryBuilder($query);
    }
}
