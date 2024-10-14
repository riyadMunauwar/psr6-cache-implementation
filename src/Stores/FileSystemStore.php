<?php

namespace Psr6CacheLibrary\Stores;

use Psr6CacheLibrary\CacheItemInterface;

class FileSystemStore
{
    private string $cacheDir;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = rtrim($cacheDir, '/') . '/';
    }

    private function getFilePath(string $key): string
    {
        return $this->cacheDir . md5($key) . '.cache';
    }

    public function get(string $key): ?CacheItemInterface
    {
        $path = $this->getFilePath($key);
        if (!file_exists($path)) {
            return null;
        }
        return unserialize(file_get_contents($path));
    }

    public function set(CacheItemInterface $item): bool
    {
        $path = $this->getFilePath($item->getKey());
        return file_put_contents($path, serialize($item)) !== false;
    }

    public function delete(string $key): bool
    {
        $path = $this->getFilePath($key);
        return file_exists($path) ? unlink($path) : false;
    }

    public function clear(): bool
    {
        foreach (glob($this->cacheDir . '*.cache') as $file) {
            unlink($file);
        }
        return true;
    }
}
