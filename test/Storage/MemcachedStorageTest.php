<?php
namespace sideshow_bob\throttle\Storage;

class MemcachedStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        $m = new \Memcached();
        $m->addServer("localhost", 11211);
        return new MemcachedStorage($m);
    }
}
