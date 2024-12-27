<?php

declare(strict_types=1);

namespace App\Models\Shared\Attributes;

/**
 * @mixin Model
 */
trait HasAttributes
{
    public function isAttributeLoaded(string $attribute): bool
    {
        return array_key_exists($attribute, $this->getAttributes());
    }
}
