<?php

declare(strict_types=1);

namespace App\Models\Shared\Searchable;

interface SearchableInterface
{
    public array $searchable { get; }

    public array $searchableAttributes { get; }

    public array $searchableRelations { get; }
}
