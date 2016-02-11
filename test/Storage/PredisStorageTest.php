<?php
namespace sideshow_bob\throttle\Storage;

class PrdisStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        return new PredisStorage("tcp://localhost");
    }
}
