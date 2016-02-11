<?php
namespace sideshow_bob\throttle\Storage;

class MemcachedStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        return new MemcachedStorage(
            [
                "localhost" => 11211,
            ]
        );
    }
}
