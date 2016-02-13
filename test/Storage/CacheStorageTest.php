<?php
namespace sideshow_bob\throttle\Storage;

use Doctrine\Common\Cache\MemcachedCache;

class CacheStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        $m = new \Memcached();
        $m->addServer("localhost", 11211);
        $c = new MemcachedCache();
        $c->setMemcached($m);
        return new CacheStorage($c);
    }
}
