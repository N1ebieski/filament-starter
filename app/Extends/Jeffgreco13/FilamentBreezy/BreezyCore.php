<?php

declare(strict_types=1);

namespace App\Extends\Jeffgreco13\FilamentBreezy;

use Jeffgreco13\FilamentBreezy\BreezyCore as BaseBreezyCore;

final class BreezyCore extends BaseBreezyCore
{
    protected function preparePages(): array
    {
        $collection = collect();

        if ($this->myProfile['condition']) {
            $collection->push($this->getMyProfilePageClass());
        }
        return $collection->toArray();
    }
}
