<?php

namespace Psr6CacheLibrary\Stores;

use Redis;
use Psr6CacheLibrary\CacheItem;
use Psr6CacheLibrary\CacheItemInterface;

class RedisStore
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function get(string $key): ?CacheItemInterface
    {
        $value = $this->redis->get($key);
        return $value ? unserialize($value) : null;
    }

    public function set(CacheItemInterface $item): bool
    {
        $ttl = $item->expiresAt ? $item->expiresAt - time() : 0;
        return $this->redis->set($item->getKey(), serialize($item), $ttl);
    }

    public function delete(string $key): bool
    {
        return $this->redis->del($key) > 0;
    }

    public function clear(): bool
    {
        return $this->redis->flushAll();
    }
}
