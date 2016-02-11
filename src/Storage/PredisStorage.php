<?php
namespace sideshow_bob\throttle\Storage;

use Predis\Client;

/**
 * StorageInterface implementation based on Predis.
 * @package sideshow_bob\throttle\Storage
 */
final class PredisStorage extends AbstractStorage
{
    private $redis = null;

    /**
     * PredisStorage constructor.
     * @param mixed $parameters Connection parameters for one or more servers.
     * @param mixed $options Options to configure some behaviours of the client.
     */
    public function __construct($parameters = null, $options = null)
    {
        if (!class_exists("Predis\\Client")) {
            throw new \RuntimeException("Predis\\Client class not found");
        }
        $this->redis = new Client($parameters, $options);
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
        $this->redis->setex(static::normalize($identifier), $ttl, $amount);
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
