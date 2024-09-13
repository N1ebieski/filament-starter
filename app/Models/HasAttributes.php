<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read array $selectable
 * @property-read array $withable
 * @property-read array $sortable
 * @mixin Model
 */
trait HasAttributes
{
    public function getSelectable(): array
    {
        return $this->selectable;
    }

    public function getWithable(): array
    {
        return $this->withable;
    }

    public function getSortable(): array
    {
        return $this->sortable;
    }

    public function attributeLoaded(string $attribute): bool
    {
        return array_key_exists($attribute, $this->getAttributes());
    }
}
