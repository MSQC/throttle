<?php
namespace sideshow_bob\throttle\Storage;

use Doctrine\Common\Cache\Cache;

/**
 * StorageInterface implementation based on the doctrine/cache package.
 * @package sideshow_bob\throttle\Storage
 */
final class CacheStorage extends AbstractStorage
{
    private $cache;

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
        $data = $this->cache->fetch($identifier);
        return is_array($data) ? $data["amount"] : 0;
    }

    /**
     * @inheritdoc
     */
    public function doSave($identifier, $amount, $ttl = 0)
    {
        $this->cache->save(
            $identifier,
            [
                "amount" => $amount,
                "expiration" => $ttl > 0 ? time() + $ttl : 0
            ],
            $ttl
        );
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 0)
    {
        $data = $this->cache->fetch($identifier);
        if (!is_array($data)) {
            // no data has been found
            $this->save($identifier, 1, $ttl);
            return 1;
        }
        // there exists data
        $amount = $data["amount"] + 1;
        // so we have to extract the custom delta ttl
        $deltaTtl = $data["expiration"] - time();
        // save the increased amount
        $this->save($identifier, $amount, $deltaTtl > 0 ? $deltaTtl : $ttl);
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
