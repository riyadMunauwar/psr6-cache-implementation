<?php

namespace Psr6CacheLibrary;

use DateTimeInterface;

class CacheItem implements CacheItemInterface
{
    private string $key;
    private mixed $value;
    private bool $hit;
    private ?int $expiration;

    public function __construct(string $key, $value = null, bool $hit = false, ?int $expiration = null)
    {
        $this->key = $key;
        $this->value = $value;
        $this->hit = $hit;
        $this->expiration = $expiration;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function get()
    {
        return $this->hit ? $this->value : null;
    }

    public function isHit(): bool
    {
        return $this->hit && ($this->expiration === null || time() < $this->expiration);
    }

    public function set($value): self
    {
        $this->value = $value;
        $this->hit = true;
        return $this;
    }

    public function expiresAt(?DateTimeInterface $expiration): self
    {
        $this->expiration = $expiration ? $expiration->getTimestamp() : null;
        return $this;
    }

    public function expiresAfter($time): self
    {
        $this->expiration = $time ? time() + $time : null;
        return $this;
    }
}
