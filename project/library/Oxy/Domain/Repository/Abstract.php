<?php
/**
 * Standard domain repository
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Repository
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
abstract class Oxy_Domain_Repository_Abstract implements Oxy_Domain_Repository_Interface
{
    /**
     * @var Oxy_EventStore_Interface
     */
    protected $_eventStore;

    /**
     * @var Oxy_EventStore_EventPublisher_Interface
     */
    protected $_eventsPublisher;

    /**
     * Initialize repository
     *
     * @param Oxy_EventStore_Interface $eventStore
     * @param Oxy_EventStore_EventPublisher_Interface $eventsPublisher
     */
    public function __construct(
        Oxy_EventStore_Interface $eventStore,
        Oxy_EventStore_EventPublisher_Interface $eventsPublisher
    ) 
    {
        $this->_eventStore = $eventStore;
        $this->_eventsPublisher = $eventsPublisher;
    }
}