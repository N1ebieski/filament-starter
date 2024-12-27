<?php

declare(strict_types=1);

namespace App\Models\Permission;

use App\Casts\ValueObject\ValueObjectCast;
use App\Models\Shared\Attributes\HasCamelCaseAttributes;
use App\ValueObjects\Permission\Name\Name;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * @mixin PermissionData
 * @property int $id
 * @property \App\ValueObjects\Permission\Name\Name $name
 *
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator filterPaginate(\App\Queries\Shared\Result\Drivers\Paginate\Paginate $paginate)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission withoutRole($roles, $guard = null)
 *
 * @mixin \Eloquent
 */
final class Permission extends BasePermission
{
    use HasCamelCaseAttributes;

    /**
     * @var string
     */
    protected $guard_name = 'web';

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

    public PermissionData $data {
        get => PermissionData::from($this);
    }
}
