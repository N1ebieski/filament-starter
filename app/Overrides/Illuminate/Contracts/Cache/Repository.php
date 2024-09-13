<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Cache;

use Illuminate\Cache\TaggedCache;

interface Repository
{
    /**
     * @param  array|mixed  $names
     * @return TaggedCache
     */
    public function tags($names);
}
