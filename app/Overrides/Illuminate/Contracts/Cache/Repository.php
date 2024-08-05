<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Contracts\Cache;

use Illuminate\Cache\TaggedCache;
use Illuminate\Contracts\Cache\Repository as BaseRepository;

interface Repository extends BaseRepository
{
    /**
     * @param  array|mixed  $names
     * @return TaggedCache
     */
    public function tags($names);
}
