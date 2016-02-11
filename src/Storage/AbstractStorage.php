<?php
namespace sideshow_bob\throttle\Storage;

use sideshow_bob\throttle\StorageInterface;

/**
 * Abstract StorageInterface implementation providing some utility methods.
 * @package sideshow_bob\throttle\Storage
 */
abstract class AbstractStorage implements StorageInterface
{
    protected static function normalize($identifier)
    {
        if (!is_string($identifier) || (is_object($identifier) && !method_exists($identifier, "__toString"))) {
            throw new \InvalidArgumentException("Invalid identifier provided");
        }
        return (string)$identifier;
    }
}
