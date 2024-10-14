<?php

use Redis;
use PDO;
use Psr6CacheLibrary\CachePool;
use Psr6CacheLibrary\Stores\RedisStore;
use Psr6CacheLibrary\Stores\FileSystemStore;
use Psr6CacheLibrary\Stores\DatabaseStore;

// Redis Store Example
$redis = new Redis();
$redis->connect('127.0.0.1');
$cachePool = new CachePool(new RedisStore($redis));

// File System Store Example
$cachePool = new CachePool(new FileSystemStore(__DIR__ . '/cache'));

// Database Store Example
$pdo = new PDO('mysql:host=localhost;dbname=cache', 'user', 'password');
$cachePool = new CachePool(new DatabaseStore($pdo));

// Usage
$item = $cachePool->getItem('foo')->set('bar');
$cachePool->save($item);
echo $cachePool->getItem('foo')->get();  // Output: bar
