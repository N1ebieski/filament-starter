<?php

declare(strict_types=1);

namespace App\Queries\SearchBy\Drivers\DatabaseMatch;

use App\Data\Data\Data;
use App\Scopes\HasSearchScopes;
use App\Queries\SearchBy\SearchByInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class DatabaseMatch extends Data implements SearchByInterface
{
    public function __construct(
        public readonly bool $isOrderBy,
        public readonly ?array $attributes = null,
        public readonly ?array $relations = null,
        public readonly ?array $exacts = null,
        public readonly ?array $looses = null
    ) {
    }

    /**
     * @param Builder|HasSearchScopes $builder
     */
    public function getSearchBuilder(Builder $builder): Builder
    {
        return $builder->filterSearchByDatabaseMatch($this)
            ->filterSearchAttributesByDatabaseMatch($this)
            ->when($this->isOrderBy, function (Builder|HasSearchScopes $builder) {
                return $builder->filterOrderByDatabaseMatch($this);
            });
    }

    public function getSearchAsString(): ?string
    {
        if (is_null($this->exacts) && is_null($this->looses)) {
            return null;
        }

        return implode(' ', array_merge(
            !is_null($this->exacts) ? $this->exacts : [],
            !is_null($this->looses) ? $this->looses : []
        ));
    }
}
