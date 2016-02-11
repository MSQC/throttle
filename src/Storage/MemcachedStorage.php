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
    public function get($identifier)
    {
        return $this->memcached->get(static::normalize($identifier));
    }

    /**
     * @inheritdoc
     */
    public function save($identifier, $amount, $ttl = 300)
    {
        $this->memcached->set(static::normalize($identifier), $amount, time() + $ttl);
    }

    /**
     * @inheritdoc
     */
    public function increment($identifier)
    {
        return $this->memcached->increment(static::normalize($identifier));
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $this->memcached->delete(static::normalize($identifier));
    }
}
