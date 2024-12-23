<?php

declare(strict_types=1);

namespace App\Models;

interface SearchableInterface
{
    public array $searchable { get; }

    public array $searchableAttributes { get; }
}
