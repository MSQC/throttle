<?php
namespace Websoftwares;
/**
 * StorageInterface
 * Interface defining methods that must be implemented in the storage classes.
 *
 * @package Websoftwares
 * @license http://www.dbad-license.org/ DbaD
 * @version 0.1
 * @author boris <boris@websoftwar.es>
 */
interface StorageInterface
{
    /**
     * save
     *
     * @param string $ip       adress to save
     * @param int    $amount   the current amount
     * @param int    $timespan the timespan in seconds
    */
    public function save($ip, $amount, $timespan);

    /**
     * increment
     *
     * @param int $ip adress to increment the value for
     */
    public function increment($ip);
}
