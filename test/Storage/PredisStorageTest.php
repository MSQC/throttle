<?php
namespace sideshow_bob\throttle\Storage;

use Predis\Client;

class PredisStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        return new PredisStorage(new Client("tcp://localhost"));
    }
}
