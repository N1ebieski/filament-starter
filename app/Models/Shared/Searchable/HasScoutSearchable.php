<?php

declare(strict_types=1);

namespace App\Models\Shared\Searchable;

/**
 * @mixin Model
 * @mixin SearchableInterface
 */
trait HasScoutSearchable
{
    public function toSearchableArray(): array
    {
        $searchable = [];

        foreach ($this->searchable as $key => $value) {
            $searchable[$key] = $this->getRawOriginal($value);
        }

        return $searchable;
    }
}
