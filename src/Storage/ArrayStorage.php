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
    public function doGet($identifier)
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
    public function doSave($identifier, $amount, $ttl = 0)
    {
        $this->storage[$identifier] = [
            "amount" => $amount,
            "expiration" => $ttl === 0 ? 0 : time() + $ttl,
        ];
    }

    /**
     * @inheritdoc
     */
    public function doIncrement($identifier, $ttl = 300)
    {
        if (($data = $this->getAndValidate($identifier)) === null || ($deltaTtl = $data["expiration"] - time()) <= 0) {
            // not found or expired
            $this->save($identifier, 1, $ttl);
            return 1;
        }
        $amount = $data["amount"] + 1;
        $this->save($identifier, $amount, $deltaTtl);
        return $amount;
    }

    /**
     * @inheritdoc
     */
    public function doDelete($identifier)
    {
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
        if (!isset($this->storage[$identifier])) {
            return null;
        }
        // fetch the data
        $data = $this->storage[$identifier];
        if ($data["expiration"] > 0 && $data["expiration"] < time()) {
            // the data has expired so we remove it
            unset($this->storage[$identifier]);
            return null;
        }
        return $this->storage[$identifier];
    }
}
