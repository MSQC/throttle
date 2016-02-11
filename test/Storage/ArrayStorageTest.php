<?php
namespace sideshow_bob\throttle\Storage;

class ArrayStorageTest extends AbstractStorageTest
{
    /**
     * @inheritdoc
     */
    protected function createStorage()
    {
        return new ArrayStorage();
    }
}
