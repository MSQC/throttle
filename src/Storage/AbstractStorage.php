<?php
namespace sideshow_bob\throttle\Storage;

use sideshow_bob\throttle\StorageException;
use sideshow_bob\throttle\StorageInterface;

/**
 * Abstract StorageInterface implementation providing some utility methods.
 * @package sideshow_bob\throttle\Storage
 */
abstract class AbstractStorage implements StorageInterface
{
    /**
     * Validate and normalize the given identifier.
     * @param mixed $identifier
     * @return string
     */
    private static function normalize($identifier)
    {
        if (!is_string($identifier) || (is_object($identifier) && !method_exists($identifier, "__toString"))) {
            throw new \InvalidArgumentException("Invalid identifier provided");
        }
        return (string)$identifier;
    }

    /**
     * @inheritdoc
     */
    final public function get($identifier)
    {
        $identifier = static::normalize($identifier);
        try {
            return $this->doGet($identifier);
        } catch (\Exception $e) {
            throw new StorageException("Could not get data from storage", $e);
        }
    }

    /**
     * Get the current amount of request for a specific identifier.
     * @param string $identifier
     * @return int
     * @see get
     */
    abstract protected function doGet($identifier);

    /**
     * @inheritdoc
     */
    public function save($identifier, $amount, $ttl = 300)
    {
        $identifier = static::normalize($identifier);
        try {
            $this->doSave($identifier, $amount, $ttl);
        } catch (\Exception $e) {
            throw new StorageException("Could not save data to storage", $e);
        }
    }

    /**
     * Save the current amount of requests for a specific identifier.
     * @param string $identifier identifier to save
     * @param int $amount the current amount
     * @param int $ttl [optional] time to live in seconds
     * @see save
     */
    abstract protected function doSave($identifier, $amount, $ttl = 300);

    /**
     * @inheritdoc
     */
    public function increment($identifier)
    {
        $identifier = static::normalize($identifier);
        try {
            return $this->doIncrement($identifier);
        } catch (\Exception $e) {
            throw new StorageException("Could not increment data from storage", $e);
        }
    }

    /**
     * Increment the amount of a specific identifier and return it's new amount.
     * @param string $identifier
     * @return int
     * @see increment
     */
    abstract protected function doIncrement($identifier);

    public function delete($identifier)
    {
        $identifier = static::normalize($identifier);
        try {
            return $this->doDelete($identifier);
        } catch (\Exception $e) {
            throw new StorageException("Could not get delete from storage", $e);
        }
    }

    /**
     * Remove a specific identifier.
     * @param string $identifier
     * @see delete
     */
    abstract protected function doDelete($identifier);
}
