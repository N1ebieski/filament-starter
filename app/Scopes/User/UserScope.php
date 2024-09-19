<?php

declare(strict_types=1);

namespace App\Scopes\User;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class UserScope implements Scope
{
    public function __construct(private readonly User $user) {}

    public function apply(Builder $builder, Model $model): void
    {
        $builder->whereBelongsTo($this->user);
    }
}
