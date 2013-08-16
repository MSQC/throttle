<?php
namespace Websoftwares;
/**
 * StorageInterface
 * Interface defining methods that must be implemented in the storage classes.
 *
 * @package Websoftwares
 * @license http://www.dbad-license.org/ DbaD
 * @version 0.3.3
 * @author boris <boris@websoftwar.es>
 */
interface StorageInterface
{
    /**
     * save
     *
     * @param mixed $identifier identifier to save
     * @param int   $amount     the current amount
     * @param int   $timespan   the timespan in seconds
    */
    public function save($identifier, $amount, $timespan);

    /**
     * update
     *
     * @param mixed $identifier identifier to update
     * @param int   $amount     the current amount
     * @param int   $timespan   the timespan in seconds
    */
    public function update($identifier, $amount, $timespan);

    /**
     * increment
     *
     * @param mixed $identifier identifier to increment the value for
     */
    public function increment($identifier);

    /**
     * delete
     *
     * @param mixed $identifier identifier to delete the entry for
     */
    public function delete($identifier);

    /**
     * get
     *
     * @param mixed $identifier identifier to retrieve the value from storage
     */
    public function get($identifier);
}
