<?php
namespace sideshow_bob\throttle\Storage;

/**
 * StorageInterface implementation based on Memcached.
 * @package sideshow_bob\throttle\Storage
 */
final class MemcachedStorage extends AbstractStorage
{
    private $memcached;

    /**
     * MemcachedStorage constructor.
     * @param \Memcached $memcached
     */
    public function __construct(\Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * @inheritdoc
     */
    public function doGet($identifier)
    {
        return ($amount = $this->memcached->get($identifier)) !== false ? $amount : 0;
    }

    /**
     * @inheritdoc
     */
    public function doSave($identifier, $amount, $ttl = 0)
    {
        $this->memcached->set($identifier, $amount, $ttl > 0 ? $ttl : null);
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 0)
    {
        if (($amount = $this->memcached->increment($identifier)) === false) {
            // the key was not yet set, so we set it to 1
            $amount = 1;
            $this->save($identifier, $amount, $ttl);
        }
        return $amount;
    }

    /**
     * @inheritdoc
     */
    public function doDelete($identifier)
    {
        $this->memcached->delete($identifier);
    }
}
