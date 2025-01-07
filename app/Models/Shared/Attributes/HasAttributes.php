<?php

declare(strict_types=1);

namespace App\Models\Shared\Attributes;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasAttributes
{
    public function isAttributeLoaded(string $name): bool
    {
        if ($this->hasGetMutator($name) || $this->hasAttributeGetMutator($name)) {
            try {
                $this->getAttribute($name);
            } catch (\Illuminate\Database\Eloquent\MissingAttributeException) {
                return false;
            }

            return true;
        }

        return array_key_exists($name, $this->getAttributes());
    }
}
