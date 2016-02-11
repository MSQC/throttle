<?php
namespace sideshow_bob\throttle\Storage;

use Predis\Client;

/**
 * StorageInterface implementation based on Predis.
 * @package sideshow_bob\throttle\Storage
 */
final class PredisStorage extends AbstractStorage
{
    private $client = null;

    /**
     * PredisStorage constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        if (!class_exists("Predis\\Client")) {
            throw new \RuntimeException("Predis\\Client class not found");
        }
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function get($identifier)
    {
        return $this->client->get(static::normalize($identifier));
    }

    /**
     * @inheritdoc
     */
    public function save($identifier, $amount, $ttl = 300)
    {
        $this->client->setex(static::normalize($identifier), $ttl, $amount);
    }

    /**
     * @inheritdoc
     */
    public function increment($identifier)
    {
        return $this->client->incr(static::normalize($identifier));
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $this->client->del(static::normalize($identifier));
    }
}
