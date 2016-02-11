<?php
namespace sideshow_bob\throttle\Storage;

use sideshow_bob\throttle\StorageInterface;

abstract class AbstractStorageTest extends \PHPUnit_Framework_TestCase
{
    /** @var  StorageInterface */
    private $storage;

    /**
     * Create the StorageInterface implementation to test.
     * @return StorageInterface
     */
    protected abstract function createStorage();

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->storage = $this->createStorage();
        $this->storage->save("identifier", 1);
    }

    public function testInvalidIdentifier()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->storage->get(new \stdClass());
    }

    public function testMissingData()
    {
        $this->assertEquals(0, $this->storage->get("non-existing-identifier"));
    }

    public function testDataExpiration()
    {
        $this->storage->save("identifier", 1, 1);
        sleep(2);
        $this->assertEquals(0, $this->storage->get("non-existing-identifier"));
    }

    public function testSaveAndGet()
    {
        $this->assertEquals(1, $this->storage->get("identifier"));
    }

    public function testSaveAndIncrement()
    {
        $this->assertEquals(2, $this->storage->increment("identifier"));
    }

    /**
     * @after testSaveAndGet
     */
    public function testSaveAndDelete()
    {
        $this->storage->delete("identifier");
        $this->assertEquals(0, $this->storage->get("identifier"));
    }
}
