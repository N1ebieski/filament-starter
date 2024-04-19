<?php

declare(strict_types=1);

namespace App\Overrides\Jeffgreco13\FilamentBreezy;

use Override;
use Illuminate\Support\Collection;
use Jeffgreco13\FilamentBreezy\BreezyCore as BaseBreezyCore;

final class BreezyCore extends BaseBreezyCore
{
    #[Override]
    protected function preparePages(): array
    {
        $collection = new Collection();

        if ($this->myProfile['condition']) {
            $collection->push($this->getMyProfilePageClass());
        }

        return $collection->toArray();
    }
}
