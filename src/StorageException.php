<?php
namespace sideshow_bob\throttle;

/**
 * Exception signaling problems with the storage.
 * @package sideshow_bob\throttle
 */
class StorageException extends \Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message = "", \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}
