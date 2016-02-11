<?php
namespace sideshow_bob\throttle\Storage;

/**
 * StorageInterface implementation based on Redis.
 * @package sideshow_bob\throttle\Storage
 */
final class RedisStorage extends AbstractStorage
{
    private $redis = null;

    /**
     * RedisStorage constructor.
     * @param string $host
     * @param int $port [optional]
     * @param float $timeout [optional]
     */
    public function __construct($host, $port = 6379, $timeout = 0.0)
    {
        if (!extension_loaded("redis")) {
            throw new \RuntimeException("Redis class not found");
        }
        $this->redis = new \Redis();
        $this->redis->connect($host, $port, $timeout);
    }

    /**
     * @inheritdoc
     */
    public function get($identifier)
    {
        return $this->redis->get(static::normalize($identifier));
    }

    /**
     * @inheritdoc
     */
    public function save($identifier, $amount, $ttl = 300)
    {
        $this->redis->set(static::normalize($identifier), $amount, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function increment($identifier)
    {
        return $this->redis->incr(static::normalize($identifier));
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $this->redis->del(static::normalize($identifier));
    }
}
