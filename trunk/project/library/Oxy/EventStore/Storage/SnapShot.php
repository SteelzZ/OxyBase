<?php
/**
 * Snapshot holder
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
class Oxy_EventStore_Storage_SnapShot implements Oxy_EventStore_Storage_SnapShot_Interface
{
    /**
     * Eventprovider GUID
     *
     * @var Oxy_Guid
     */
    private $_eventProviderId;

    /**
     * Event ptovider version
     *
     * @var Integer
     */
    private $_version;

    /**
     * Memento
     *
     * @var Oxy_EventStore_Storage_Memento_Interface
     */
    private $_memento;

    /**
     * Initialize snapshot
     *
     * @param Oxy_Guid $eventProviderId
     * @param integer $version
     * @param Oxy_EventStore_Storage_Memento_Interface $memento
     *
     * @return void
     */
    public function __construct(
        Oxy_Guid $eventProviderId, $version,
        Oxy_EventStore_Storage_Memento_Interface $memento)
    {
        $this->_eventProviderId = $eventProviderId;
        $this->_version = $version;
        $this->_memento = $memento;
    }

    /**
     * Return event provider id
     *
     * @return Oxy_Guid
     */
    public function getEventProviderId()
    {
        return $this->_eventProviderId;
    }

    /**
     * Return memento
     *
     * @return Oxy_EventStore_Storage_Memento_Interface
     */
    public function getMemento()
    {
        return $this->_memento;
    }

    /**
     * Return version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->_version;
    }
}