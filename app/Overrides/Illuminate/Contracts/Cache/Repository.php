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

    /**
     * Retrieve an item from the cache by key, refreshing it in the background if it is stale.
     *
     * @template TCacheValue
     *
     * @param  array{ 0: \DateTimeInterface|\DateInterval|int, 1: \DateTimeInterface|\DateInterval|int }  $ttl
     * @param  (callable(): TCacheValue)  $callback
     * @param  array{ seconds?: int, owner?: string }|null  $lock
     * @return TCacheValue
     */
    public function flexible(string $key, array $ttl, callable $callback, ?array $lock = null): mixed;
}
