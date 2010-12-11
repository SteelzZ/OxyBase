<?php
/**
 * SnapShot storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 */
interface Oxy_EventStore_Storage_SnapShot_Interface
{
    /**
     * Return memento
     *
     * @return Oxy_EventStore_Storage_Memento_Interface
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