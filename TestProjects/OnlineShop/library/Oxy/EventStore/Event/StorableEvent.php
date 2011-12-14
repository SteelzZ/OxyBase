<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Event;
use Oxy\EventStore\Event\StorableEventInterface;
use Oxy\Guid;
use Oxy\EventStore\Event\EventInterface;

class StorableEvent implements StorableEventInterface
{
    /**
     * @var Guid
     */
    private $_providerGuid;

    /**
     * @var EventInterface
     */
    private $_event;

    /**
     * @param Guid $providerGuid
     * @param EventInterface $event
     */
    public function __construct(
        Guid $providerGuid, 
        EventInterface $event
    )
    {
        $this->_providerGuid = $providerGuid;
        $this->_event = $event;
    }

    /**
     * @return EventInterface
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @return Guid
     */
    public function getProviderGuid()
    {
        return $this->_providerGuid;
    }
}