<?php

declare(strict_types=1);

namespace App\Models;

interface HasAttributesInterface
{
    public function getSelectable(): array;

    public function getWithable(): array;

    public function getSortable(): array;

    public function attributeLoaded(string $attribute): bool;
}
