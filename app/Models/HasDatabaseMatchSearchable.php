<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property array $searchable
 * @property array $searchableAttributes
 */
trait HasDatabaseMatchSearchable
{
    public function getSearchable(): array
    {
        return $this->searchable;
    }

    public function getSearchableAttributes(): array
    {
        return $this->searchableAttributes;
    }
}
