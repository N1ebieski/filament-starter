<?php

declare(strict_types=1);

namespace App\Actions\PWA\GetAssets\Assets;

use Illuminate\Support\Collection;

final class Assets
{
    public function __construct(
        public Collection $value = new Collection()
    ) {
    }
}
