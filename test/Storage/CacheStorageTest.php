<?php
namespace sideshow_bob\throttle\Storage;

use Doctrine\Common\Cache\ArrayCache;

class CacheStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        return new CacheStorage(new ArrayCache());
    }
}
