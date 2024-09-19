<?php

declare(strict_types=1);

namespace App\Queries\Shared\SearchBy\Drivers\DatabaseMatch;

use App\Data\Data\Data;
use App\Queries\Shared\SearchBy\SearchByInterface;
use App\Support\Attributes\Handler\Handler;

#[Handler(DatabaseMatchHandler::class)]
final class DatabaseMatch extends Data implements SearchByInterface
{
    public function __construct(
        public readonly bool $isOrderBy,
        public readonly ?array $attributes = null,
        public readonly ?array $relations = null,
        public readonly ?array $exacts = null,
        public readonly ?array $looses = null
    ) {}

    public function getSearchAsString(): ?string
    {
        if (is_null($this->exacts) && is_null($this->looses)) {
            return null;
        }

        return implode(' ', array_merge(
            ! is_null($this->exacts) ? $this->exacts : [],
            ! is_null($this->looses) ? $this->looses : []
        ));
    }
}
