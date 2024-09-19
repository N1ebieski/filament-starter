<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read array|null $selectAlways
 * @property-read array|null $selectable
 * @property-read array|null $withable
 * @property-read array|null $sortable
 *
 * @mixin Model
 */
trait HasAttributes
{
    public function getSelectAlways(): array
    {
        return $this->selectAlways ?? [];
    }

    public function getSelectable(): array
    {
        return $this->selectable ?? [];
    }

    public function getWithable(): array
    {
        return $this->withable ?? [];
    }

    public function getSortable(): array
    {
        return $this->sortable ?? [];
    }

    public function attributeLoaded(string $attribute): bool
    {
        return array_key_exists($attribute, $this->getAttributes());
    }
}
