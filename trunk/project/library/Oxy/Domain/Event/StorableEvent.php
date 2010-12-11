<?php
class Oxy_Domain_StorableEvent 
    implements Oxy_Domain_Event_StorableEventInterface
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
     * @param Oxy_Domain_EventInterface $event
     */
    public function __construct($providerGuid, $event)
    {
        $this->_providerGuid = $providerGuid;
        $this->_event = $event;
    }

    /**
     * @return Oxy_Domain_EventInterface
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