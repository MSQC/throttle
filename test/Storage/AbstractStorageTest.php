<?php
namespace sideshow_bob\throttle\Storage;

use sideshow_bob\throttle\StorageInterface;

abstract class AbstractStorageTest extends \PHPUnit_Framework_TestCase
{
    const IDENTIFIER = "identifier";
    const NX_IDENTIFIER = "non-existing-identifier";
    const TTL = 3;

    /** @var  StorageInterface */
    private $storage;

    /**
     * Create the StorageInterface implementation to test.
     * @return StorageInterface
     */
    abstract protected function createStorage();

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->storage = $this->createStorage();
        $this->storage->save(static::IDENTIFIER, 1, static::TTL);
    }

    public function testInvalidIdentifier()
    {
        $this->setExpectedException("InvalidArgumentException");
        $this->storage->get(new \stdClass());
    }

    public function testMissingData()
    {
        $this->assertEquals(0, $this->storage->get(static::NX_IDENTIFIER));
    }

    public function testDataExpiration()
    {
        sleep(static::TTL + 1);
        $this->assertEquals(0, $this->storage->get(static::NX_IDENTIFIER));
    }

    public function testSaveAndGet()
    {
        $this->assertEquals(1, $this->storage->get(static::IDENTIFIER));
    }

    public function testSaveAndIncrement()
    {
        $this->assertEquals(2, $this->storage->increment(static::IDENTIFIER));
    }

    public function testSaveAndDelete()
    {
        $this->storage->delete(static::IDENTIFIER);
        $this->assertEquals(0, $this->storage->get(static::IDENTIFIER));
    }

    public function testIncrementAndExpire()
    {
        $this->assertEquals(1, $this->storage->get(static::IDENTIFIER));
        $this->assertEquals(2, $this->storage->increment(static::IDENTIFIER, static::TTL));
        sleep(static::TTL + 1);
        $this->assertEquals(0, $this->storage->get(static::IDENTIFIER));
        $this->assertEquals(1, $this->storage->increment(static::IDENTIFIER, static::TTL));
    }
}
