<?php
namespace sideshow_bob\throttle\Storage;

/**
 * StorageInterface implementation based on Memcached.
 * @package sideshow_bob\throttle\Storage
 */
final class MemcachedStorage extends AbstractStorage
{
    private $memcached = null;

    /**
     * MemcachedStorage constructor.
     * @param array $servers
     */
    public function __construct(array $servers = [])
    {
        if (!extension_loaded("memcached")) {
            throw new \RuntimeException("Memcached class not found");
        }
        $this->memcached = new \Memcached();
        foreach ($servers as $server => $port) {
            $this->memcached->addServer($server, $port);
        }
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
    public function doSave($identifier, $amount, $ttl = 300)
    {
        $this->memcached->set($identifier, $amount, time() + $ttl);
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 300)
    {
        return $this->memcached->increment($identifier, 1, 0, time() + $ttl);
    }

    /**
     * @inheritdoc
     */
    public function doDelete($identifier)
    {
        $this->memcached->delete($identifier);
    }
}
