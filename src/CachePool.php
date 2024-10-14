<?php

namespace Psr6CacheLibrary;

class CachePool implements CachePoolInterface
{
    private $store;

    public function __construct($store)
    {
        $this->store = $store;
    }

    public function getItem(string $key): CacheItemInterface
    {
        return $this->store->get($key) ?? new CacheItem($key, null, false);
    }

    public function getItems(array $keys = []): iterable
    {
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }
        return $items;
    }

    public function hasItem(string $key): bool
    {
        return $this->getItem($key)->isHit();
    }

    public function clear(): bool
    {
        return $this->store->clear();
    }

    public function deleteItem(string $key): bool
    {
        return $this->store->delete($key);
    }

    public function deleteItems(array $keys): bool
    {
        foreach ($keys as $key) {
            $this->deleteItem($key);
        }
        return true;
    }

    public function save(CacheItemInterface $item): bool
    {
        return $this->store->set($item);
    }

    public function saveDeferred(CacheItemInterface $item): bool
    {
        // Example of saving deferred (in-memory for this runtime)
        $this->deferred[$item->getKey()] = $item;
        return true;
    }

    public function commit(): bool
    {
        foreach ($this->deferred as $key => $item) {
            $this->save($item);
            unset($this->deferred[$key]);
        }
        return true;
    }
}
