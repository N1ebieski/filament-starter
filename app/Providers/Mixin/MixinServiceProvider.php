<?php

declare(strict_types=1);

namespace App\Providers\Mixin;

use App\Mixins\Collection\Data\DataMixin;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

final class MixinServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Collection::mixin(new DataMixin);
    }
}
