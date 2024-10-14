<?php

namespace Psr6CacheLibrary\Stores;

use PDO;
use Psr6CacheLibrary\CacheItemInterface;

class DatabaseStore
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function get(string $key): ?CacheItemInterface
    {
        $stmt = $this->db->prepare('SELECT value FROM cache WHERE key = :key');
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetchColumn();
        return $result ? unserialize($result) : null;
    }

    public function set(CacheItemInterface $item): bool
    {
        $stmt = $this->db->prepare('REPLACE INTO cache (key, value) VALUES (:key, :value)');
        return $stmt->execute([
            'key' => $item->getKey(),
            'value' => serialize($item),
        ]);
    }

    public function delete(string $key): bool
    {
        $stmt = $this->db->prepare('DELETE FROM cache WHERE key = :key');
        return $stmt->execute(['key' => $key]);
    }

    public function clear(): bool
    {
        return $this->db->exec('DELETE FROM cache') !== false;
    }
}
