<?php
/**
 * Snapshot
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Storage;
use Oxy\EventStore\Storage\SnapShot\SnapShotInterface;
use Oxy\EventStore\Storage\Memento\MementoInterface;

class SnapShot implements SnapShotInterface
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
     * @var MementoInterface
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
     * @return MementoInterface
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
     * @param MementoInterface $memento
     *
     * @return void
     */
    public function __construct(
        Oxy_Guid $eventProviderGuid, 
        $version,
        MementoInterface $memento
    )
    {
        $this->_eventProviderGuid = $eventProviderGuid;
        $this->_version = $version;
        $this->_memento = $memento;
    }
}