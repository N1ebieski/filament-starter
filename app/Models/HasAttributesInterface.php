<?php

declare(strict_types=1);

namespace App\Models;

interface HasAttributesInterface
{
    public function attributeLoaded(string $attribute): bool;
}
