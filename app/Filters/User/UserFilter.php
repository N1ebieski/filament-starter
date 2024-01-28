<?php

declare(strict_types=1);

namespace App\Filters\User;

use App\Filters\Filter;
use App\Queries\Search;
use App\Models\Role\Role;
use App\ValueObjects\User\StatusEmail;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property-read Collection<Role> $roles
 */
final class UserFilter extends Filter
{
    public function __construct(
        public readonly ?StatusEmail $status_email = null,
        public readonly Collection $roles = new Collection(),
        public readonly ?Search $search = null,
        public readonly ?array $except = null,
    ) {
    }
}
