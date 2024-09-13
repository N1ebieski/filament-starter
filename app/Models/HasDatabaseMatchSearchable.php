<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property array|null $searchable
 * @property array|null $searchableAttributes
 */
trait HasDatabaseMatchSearchable
{
    public function getSearchable(): array
    {
        return $this->searchable ?? [];
    }

    public function getSearchableAttributes(): array
    {
        return $this->searchableAttributes ?? [];
    }
}
