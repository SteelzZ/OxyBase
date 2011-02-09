<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_EventStore_Event_StorableEvent 
    implements Oxy_EventStore_Event_StorableEventInterface
{
    /**
     * @var Oxy_Guid
     */
    private $_providerGuid;

    /**
     * @var Oxy_Domain_EventInterface
     */
    private $_event;

    /**
     * @param Oxy_Guid $providerGuid
     * @param Oxy_EventStore_Event_Interface $event
     */
    public function __construct(
        Oxy_Guid $providerGuid, 
        Oxy_EventStore_Event_Interface $event
    )
    {
        $this->_providerGuid = $providerGuid;
        $this->_event = $event;
    }

    /**
     * @return Oxy_EventStore_Event_EventInterface
     */
    public function getEvent()
    {
        return $this->_event;
    }

    /**
     * @return Oxy_Guid
     */
    public function getProviderGuid()
    {
        return $this->_providerGuid;
    }
}