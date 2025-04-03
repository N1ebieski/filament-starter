<?php

declare(strict_types=1);

namespace App\Overrides\Illuminate\Cache;

use App\Overrides\Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Cache\Repository as BaseRepository;
use Illuminate\Cache\TaggedCache;

final class Repository implements CacheRepository
{
    public function __construct(private readonly BaseRepository $cache) {}

    /**
     * @param  array|mixed  $names
     * @return TaggedCache
     */
    public function tags($names)
    {
        return $this->cache->tags($names);
    }
}
