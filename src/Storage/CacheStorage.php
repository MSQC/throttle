<?php
namespace sideshow_bob\throttle\Storage;

use Doctrine\Common\Cache\Cache;

/**
 * StorageInterface implementation based on the doctrine/cache package.
 * @package sideshow_bob\throttle\Storage
 */
final class CacheStorage extends AbstractStorage
{
    private $cache = null;

    /**
     * RedisStorage constructor.
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @inheritdoc
     */
    public function get($identifier)
    {
        $amount = $this->cache->fetch(static::normalize($identifier));
        return is_int($amount) ? $amount : 0;
    }

    /**
     * @inheritdoc
     */
    public function save($identifier, $amount, $ttl = 300)
    {
        $this->cache->save(static::normalize($identifier), $amount, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function increment($identifier)
    {
        $amount = $this->get($identifier) + 1;
        $this->save($identifier, $amount);
        return $amount;
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $this->cache->delete(static::normalize($identifier));
    }
}
