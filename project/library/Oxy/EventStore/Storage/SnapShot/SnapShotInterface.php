<?php
/**
 * SnapShot storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Storage_SnapShot_SnapShotInterface
{
    /**
     * Return memento
     *
     * @return Oxy_EventStore_Storage_Memento_MementoInterface
     */
    public function getMemento();

    /**
     * Return event provider GUID
     *
     * @return Oxy_Guid
     */
    public function getEventProviderGuid();

    /**
     * Return version
     *
     * @return integer
     */
    public function getVersion();
}