<?php

declare(strict_types=1);

namespace App\Scopes\User;

use App\Models\User\User;
use App\Scopes\HasFilterableScopes;
use App\ValueObjects\User\StatusEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Database\Eloquent\Builder;

/**
 * @mixin User
 */
trait HasUserScopes
{
    use HasFilterableScopes;

    public function scopeFilterStatusEmail(Builder $builder, ?StatusEmail $status = null): Builder
    {
        return $builder->when(!is_null($status), function (Builder $builder) use ($status) {
            return $builder->when($status->isEquals(StatusEmail::VERIFIED), function (Builder $builder) {
                return $builder->whereNotNull('email_verified_at');
            }, function (Builder $builder) {
                return $builder->whereNull('email_verified_at');
            });
        });
    }

    public function scopeFilterRoles(Builder $builder, Collection $roles = new Collection()): Builder
    {
        return $builder->when($roles->isNotEmpty(), function (Builder $builder) use ($roles) {
            return $builder->whereHas('roles', function (Builder $builder) use ($roles) {
                //@phpstan-ignore-next-line
                return $builder->whereIn('id', $roles->pluck('id'));
            });
        });
    }

    public function scopeWithAllRelations(Builder $builder): Builder
    {
        return $builder->with('roles');
    }
}
