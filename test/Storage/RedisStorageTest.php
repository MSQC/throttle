<?php
namespace sideshow_bob\throttle\Storage;

class RedisStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        return new RedisStorage("localhost");
    }
}
