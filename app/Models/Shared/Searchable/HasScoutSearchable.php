<?php

declare(strict_types=1);

namespace App\Models\Shared\Searchable;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @mixin Model
 * @mixin SearchableInterface
 */
trait HasScoutSearchable
{
    use Searchable;

    public function toSearchableArray(): array
    {
        $searchable = [];

        $searchable[$this->getKeyName()] = (string) $this->getKey();

        foreach ($this->searchable as $attribute) {
            $searchable[$attribute] = $this->getRawOriginal($attribute);
        }

        return $searchable;
    }
}
