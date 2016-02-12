<?php
namespace sideshow_bob\throttle\Storage;

use Predis\Client;
use Predis\Pipeline\Pipeline;

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
    public function doGet($identifier)
    {
        return $this->client->get($identifier);
    }

    /**
     * @inheritdoc
     */
    public function doSave($identifier, $amount, $ttl = 300)
    {
        $this->client->setex($identifier, $ttl, $amount);
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 300)
    {
        $responses = $this->client->pipeline(
            ["atomic" => true],
            function (Pipeline $pipe) use ($identifier, $ttl) {
                $pipe->incr($identifier);
                $pipe->expire($identifier, $ttl);
            }
        );
        return $responses[0];
    }

    /**
     * @inheritdoc
     */
    public function doDelete($identifier)
    {
        $this->client->del($identifier);
    }
}
