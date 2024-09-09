<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Model;

/**
 * @mixin Model
 */
trait HasScoutSearchable
{
    use HasDatabaseMatchSearchable;

    public function toSearchableArray(): array
    {
        $searchable = [];

        foreach ($this->getSearchable() as $key => $value) {
            $searchable[$key] = $this->getRawOriginal($value);
        }

        return $searchable;
    }
}
