<?php
namespace sideshow_bob\throttle\Storage;

class RedisStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        $r = new \Redis();
        $r->connect("localhost");
        return new RedisStorage($r);
    }
}
