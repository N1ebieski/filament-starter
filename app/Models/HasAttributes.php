<?php

declare(strict_types=1);

namespace App\Models;

trait HasAttributes
{
    public function attributeLoaded(string $attribute): bool
    {
        return array_key_exists($attribute, $this->getAttributes());
    }
}
