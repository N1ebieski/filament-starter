<?php

declare(strict_types=1);

namespace App\Filament\Resources\Shared;

use Illuminate\Contracts\Database\Eloquent\Builder;

interface GlobalSearchInterface
{
    public static function applyGlobalSearchAttributeConstraints(Builder $query, string $search): void;
}
