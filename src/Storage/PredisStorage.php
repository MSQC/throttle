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
    public function doSave($identifier, $amount, $ttl = 0)
    {
        if ($ttl > 0) {
            $this->client->setex($identifier, $ttl, $amount);
        } else {
            $this->client->set($identifier, $amount);
        }
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 0)
    {
        $deltaTtl = $this->client->ttl($identifier);
        $responses = $this->client->pipeline(
            ["atomic" => true],
            function (Pipeline $pipe) use ($identifier, $deltaTtl, $ttl) {
                $pipe->incr($identifier);
                $pipe->expire($identifier, $deltaTtl > 0 ? $deltaTtl : $ttl);
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
