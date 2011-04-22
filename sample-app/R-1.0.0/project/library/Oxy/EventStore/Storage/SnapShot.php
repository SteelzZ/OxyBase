<?php
/**
 * Snapshot
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_EventStore_Storage_SnapShot implements Oxy_EventStore_Storage_SnapShot_SnapShotInterface
{
    /**
     * @var Oxy_Guid
     */
    private $_eventProviderGuid;

    /**
     * @var Integer
     */
    private $_version;

    /**
     * Memento
     *
     * @var Oxy_EventStore_Storage_Memento_MementoInterface
     */
    private $_memento;

    /**
     * @return Oxy_Guid
     */
    public function getEventProviderGuid()
    {
        return $this->_eventProviderGuid;
    }

    /**
     * Return memento
     *
     * @return Oxy_EventStore_Storage_Memento_MementoInterface
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

    /**
     * Initialize snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * @param integer $version
     * @param Oxy_EventStore_Storage_Memento_MementoInterface $memento
     *
     * @return void
     */
    public function __construct(
        Oxy_Guid $eventProviderGuid, 
        $version,
        Oxy_EventStore_Storage_Memento_MementoInterface $memento
    )
    {
        $this->_eventProviderGuid = $eventProviderGuid;
        $this->_version = $version;
        $this->_memento = $memento;
    }
}