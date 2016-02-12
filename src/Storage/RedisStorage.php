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
     * @throws \RedisException
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
    public function doGet($identifier)
    {
        return $this->redis->get($identifier);
    }

    /**
     * @inheritdoc
     */
    public function doSave($identifier, $amount, $ttl = 300)
    {
        $this->redis->set($identifier, $amount, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 300)
    {
        $amount = $this->redis->incr($identifier);
        $this->redis->expire($identifier, $ttl);
        return $amount;
    }

    /**
     * @inheritdoc
     */
    public function doDelete($identifier)
    {
        $this->redis->del($identifier);
    }
}
