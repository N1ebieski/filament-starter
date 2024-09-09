<?php

namespace App\Models\User;

use Closure;
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
use App\Models\HasDatabaseMatchSearchable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\ValueObjects\User\StatusEmail\StatusEmail;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Overrides\Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

/**
 *
 *
 * @property int $id
 * @property \App\ValueObjects\User\Name\Name $name
 * @property \App\ValueObjects\User\Email\Email $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $breezy_session
 * @property-read Collection<int, \Jeffgreco13\FilamentBreezy\Models\BreezySession> $breezySessions
 * @property-read int|null $breezy_sessions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Tenant> $ownedTenants
 * @property-read int|null $owned_tenants_count
 * @property-read Collection<int, \App\Models\Permission\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, \App\Models\Role\Role> $roles
 * @property-read int|null $roles_count
 * @property-read StatusEmail $status_email
 * @property-read Collection<int, \App\Models\Permission\Permission> $tenantPermissions
 * @property-read int|null $tenant_permissions_count
 * @property-read Collection<int, \App\Models\Role\Role> $tenantRoles
 * @property-read int|null $tenant_roles_count
 * @property-read Collection<int, Tenant> $tenants
 * @property-read int|null $tenants_count
 * @property-read Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read mixed $two_factor_recovery_codes
 * @property-read mixed $two_factor_secret
 * @method static \Database\Factories\User\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filterExcept(?array $except)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterGet(\App\Queries\Get $get)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterOrderBy(?\App\Queries\OrderBy $orderby)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterOrderBySearchByDatabaseMatch(?\App\Queries\Search\DatabaseMatch $search)
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Paginate $paginate)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterResult(\App\Queries\Paginate|\App\Queries\Get|null $result)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterRoles(\Illuminate\Database\Eloquent\Collection $roles)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterSearchAttributesByDatabaseMatch(?\App\Queries\Search\DatabaseMatch $search)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterSearchBy(?\App\Queries\Search\DatabaseMatch $search, bool $isOrderBy, \App\Scopes\Search\Driver $driver = \App\Scopes\Search\Driver::DatabaseMatch)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterSearchByDatabaseMatch(?\App\Queries\Search\DatabaseMatch $search, string $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|User filterSearchByScout(?\App\Queries\Search\DatabaseMatch $search)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterStatusEmail(?\App\ValueObjects\User\StatusEmail\StatusEmail $status)
 * @method static \Illuminate\Database\Eloquent\Builder|User filterTenants(\Illuminate\Database\Eloquent\Collection $tenants)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User withAllRelations()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser, HasTenants, MustVerifyEmail
{
    use HasRoles;
    use HasUserScopes;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use PivotEventTrait;
    use TwoFactorAuthenticatable;
    use HasDatabaseMatchSearchable;

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

    private array $searchable = ['name', 'email'];

    private array $searchableAttributes = ['id'];

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
     * @param Tenant $tenant
     * @return bool
     */
    public function canAccessTenant(Model $tenant): bool
    {
        return $tenant->user->id === $this->id;
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
