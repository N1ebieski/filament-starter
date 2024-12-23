<?php

declare(strict_types=1);

namespace App\Models;

interface AttributesInterface
{
    public array $selectAlways { get; }

    public array $selectable { get; }

    public array $withable { get; }

    public array $sortable { get; }

    public function isAttributeLoaded(string $attribute): bool;
}
