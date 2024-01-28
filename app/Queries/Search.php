<?php

declare(strict_types=1);

namespace App\Queries;

final class Search
{
    public function __construct(
        public readonly ?array $attributes = null,
        public readonly ?array $relations = null,
        public readonly ?array $exacts = null,
        public readonly ?array $looses = null
    ) {
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
