<?php

declare(strict_types=1);

namespace App\Models\Permission;

use App\Casts\ValueObject\ValueObjectCast;
use App\Models\Shared\Attributes\HasCamelCaseAttributes;
use App\Models\Shared\Data\DataInterface;
use App\ValueObjects\Permission\Name\Name;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * @mixin PermissionData
 * @mixin \Eloquent
 * @property int $id
 * @property \App\ValueObjects\Permission\Name\Name $name
 */
final class Permission extends BasePermission implements DataInterface
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

    public PermissionData $data { get => PermissionData::from($this); }
}
