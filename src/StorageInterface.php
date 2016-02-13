<?php
namespace sideshow_bob\throttle;

/**
 * StorageInterface defining methods to track the data of a different requests.
 * @package sideshow_bob\throttle
 */
interface StorageInterface
{
    /**
     * Get the current amount of request for a specific identifier.
     * @param string $identifier
     * @return int
     * @throws StorageException
     */
    public function get($identifier);

    /**
     * Save the current amount of requests for a specific identifier.
     * @param string $identifier identifier to save
     * @param int $amount the current amount
     * @param int $ttl [optional] time to live in seconds
     * @throws StorageException
     */
    public function save($identifier, $amount, $ttl = 0);

    /**
     * Increment the amount of a specific identifier and return it's new amount.
     * @param string $identifier
     * @param int $ttl [optional] time to live in seconds
     * @return int
     * @throws StorageException
     */
    public function increment($identifier, $ttl = 0);

    /**
     * Remove a specific identifier.
     * @param string $identifier
     * @throws StorageException
     */
    public function delete($identifier);
}
