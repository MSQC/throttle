<?php
namespace sideshow_bob\throttle\Storage;

/**
 * StorageInterface implementation based on a simple in memory array.
 * @package sideshow_bob\throttle\Storage
 */
final class ArrayStorage extends AbstractStorage
{
    private $storage = [];

    /**
     * @inheritdoc
     */
    public function get($identifier)
    {
        if (($data = $this->getAndValidate($identifier)) === null) {
            // no data found
            return 0;
        }
        return $data["amount"];
    }

    /**
     * @inheritdoc
     */
    public function save($identifier, $amount, $ttl = 300)
    {
        $this->storage[static::normalize($identifier)] = [
            "amount" => $amount,
            "ttl" => $ttl,
            "expiration" => time() + $ttl,
        ];
    }

    /**
     * @inheritdoc
     */
    public function increment($identifier)
    {
        $data = $this->getAndValidate($identifier);
        $this->save($identifier, $data["amount"] + 1, $data["ttl"]);
        return $this->get($identifier);
    }

    /**
     * @inheritdoc
     */
    public function delete($identifier)
    {
        $identifier = static::normalize($identifier);
        if (isset($this->storage[$identifier])) {
            unset($this->storage[$identifier]);
        }
    }

    /**
     * Get saved data and validate if the data has expired.
     * @param string $identifier
     * @return array|null
     */
    private function getAndValidate($identifier)
    {
        $identifier = static::normalize($identifier);
        if (!isset($this->storage[$identifier])) {
            return null;
        }
        // fetch the data
        $data = $this->storage[$identifier];
        if ($data["expiration"] < time()) {
            // the data has expired so we remove it
            unset($this->storage[$identifier]);
            return null;
        }
        return $this->storage[$identifier];
    }
}
