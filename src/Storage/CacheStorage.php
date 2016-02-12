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
    public function doGet($identifier)
    {
        $amount = $this->cache->fetch($identifier);
        return is_int($amount) ? $amount : 0;
    }

    /**
     * @inheritdoc
     */
    public function doSave($identifier, $amount, $ttl = 300)
    {
        $this->cache->save($identifier, $amount, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 300)
    {
        $amount = $this->get($identifier) + 1;
        $this->save($identifier, $amount, $ttl);
        return $amount;
    }

    /**
     * @inheritdoc
     */
    public function doDelete($identifier)
    {
        $this->cache->delete($identifier);
    }
}
