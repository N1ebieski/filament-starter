<?php

namespace App\Models\User;

use App\Models\Shared\Attributes\AttributesInterface;
use App\Models\Shared\Attributes\HasAttributes;
use App\Models\Shared\Searchable\SearchableInterface;
use App\Models\Tenant\Tenant;
use App\Overrides\Spatie\Permission\Traits\HasRoles;
use App\QueryBuilders\User\UserQueryBuilder;
use App\ValueObjects\User\Email\Email;
use App\ValueObjects\User\Name\Name;
use App\ValueObjects\User\StatusEmail\StatusEmail;
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
 * 
 *
 * @property int $id
 * @property \App\ValueObjects\User\Name\Name $name
 * @property \App\ValueObjects\User\Email\Email $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $breezy_session
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Jeffgreco13\FilamentBreezy\Models\BreezySession> $breezySessions
 * @property-read int|null $breezy_sessions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tenant> $ownedTenants
 * @property-read int|null $owned_tenants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role\Role> $roles
 * @property-read int|null $roles_count
 * @property-read StatusEmail $status_email
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Permission\Permission> $tenantPermissions
 * @property-read int|null $tenant_permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role\Role> $tenantRoles
 * @property-read int|null $tenant_roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tenant> $tenants
 * @property-read int|null $tenants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read mixed $two_factor_recovery_codes
 * @property-read mixed $two_factor_secret
 * @method static \Database\Factories\User\UserFactory factory($count = null, $state = [])
 * @method static UserQueryBuilder<static>|User filterGet(\App\Queries\Shared\Result\Drivers\Get\Get $get)
 * @method static UserQueryBuilder<static>|User filterIgnore(?array $ignore)
 * @method static UserQueryBuilder<static>|User filterOrderBy(?\App\Queries\Shared\OrderBy\OrderBy $orderBy)
 * @method static UserQueryBuilder<static>|User filterOrderByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Shared\Result\Drivers\Paginate\Paginate $paginate)
 * @method static UserQueryBuilder<static>|User filterResult(?\App\Queries\Shared\Result\ResultInterface $result)
 * @method static UserQueryBuilder<static>|User filterRoles(?\Illuminate\Support\Collection $roles)
 * @method static UserQueryBuilder<static>|User filterSearchAttributesByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch)
 * @method static UserQueryBuilder<static>|User filterSearchBy(?\App\Queries\Shared\SearchBy\SearchByInterface $searchBy)
 * @method static UserQueryBuilder<static>|User filterSearchByDatabaseMatch(\App\Queries\Shared\SearchBy\Drivers\DatabaseMatch\DatabaseMatch $databaseMatch, string $boolean = 'and')
 * @method static UserQueryBuilder<static>|User filterSearchByScout(\App\Queries\Shared\SearchBy\Drivers\Scout\Scout $scout)
 * @method static UserQueryBuilder<static>|User filterSelect(?array $select)
 * @method static UserQueryBuilder<static>|User filterStatusEmail(?\App\ValueObjects\User\StatusEmail\StatusEmail $status)
 * @method static UserQueryBuilder<static>|User filterTenants(?\Illuminate\Support\Collection $tenants)
 * @method static UserQueryBuilder<static>|User filterWith(?array $with, bool $withAll = false)
 * @method static UserQueryBuilder<static>|User newModelQuery()
 * @method static UserQueryBuilder<static>|User newQuery()
 * @method static UserQueryBuilder<static>|User permission($permissions, $without = false)
 * @method static UserQueryBuilder<static>|User query()
 * @method static UserQueryBuilder<static>|User role($roles, $guard = null, $without = false)
 * @method static UserQueryBuilder<static>|User withAll()
 * @method static UserQueryBuilder<static>|User withoutPermission($permissions)
 * @method static UserQueryBuilder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements FilamentUser, AttributesInterface, SearchableInterface, HasTenants, MustVerifyEmail
{
    use HasApiTokens;
    use HasAttributes;
    use HasFactory;
    use HasRoles;
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
        'name' => Name::class,
        'email' => Email::class,
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
