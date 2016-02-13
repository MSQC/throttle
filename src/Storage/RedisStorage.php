<?php
namespace sideshow_bob\throttle\Storage;

/**
 * StorageInterface implementation based on Redis.
 * @package sideshow_bob\throttle\Storage
 */
final class RedisStorage extends AbstractStorage
{
    private $redis;

    /**
     * RedisStorage constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
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
    public function doSave($identifier, $amount, $ttl = 0)
    {
        $this->redis->set($identifier, $amount, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 0)
    {
        $deltaTtl = $this->redis->ttl($identifier);
        $amount = $this->redis->incr($identifier);
        $this->redis->expire($identifier, $deltaTtl > 0 ? $deltaTtl : $ttl);
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
