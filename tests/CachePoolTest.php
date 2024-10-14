<?php

use PHPUnit\Framework\TestCase;
use Psr6CacheLibrary\CachePool;
use Psr6CacheLibrary\CacheItem;

class CachePoolTest extends TestCase
{
    public function testSaveAndGetItem()
    {
        $pool = new CachePool();
        $item = new CacheItem('test_key');
        $item->set('test_value');

        $pool->save($item);

        $retrieved = $pool->getItem('test_key');
        $this->assertTrue($retrieved->isHit());
        $this->assertEquals('test_value', $retrieved->get());
    }

    public function testClearCache()
    {
        $pool = new CachePool();
        $item = new CacheItem('test_key');
        $pool->save($item);

        $pool->clear();
        $this->assertFalse($pool->hasItem('test_key'));
    }
}
