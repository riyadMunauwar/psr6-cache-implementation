<?php

namespace Psr6CacheLibrary;

use DateTimeInterface;

interface CacheItemInterface
{
    public function getKey(): string;
    public function get();
    public function isHit(): bool;
    public function set($value): self;
    public function expiresAt(?DateTimeInterface $expiration): self;
    public function expiresAfter($time): self;
}
