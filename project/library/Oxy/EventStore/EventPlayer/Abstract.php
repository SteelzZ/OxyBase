<?php
/**
 * Events publisher base class
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventPublisher
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
abstract class Oxy_EventStore_EventPlayer_EventPlayeAbstract
    implements Oxy_EventStore_EventPlayer_Interface
{
    /**
     * Events publisher
     *
     * @var Oxy_EventStore_EventPublisher_Interface
     */
    protected $_eventsPublisher;

    /**
     * Initialize publisher
     *
     * @param Oxy_EventStore_EventPublisher_Interface $eventsPublisher
     *
     * @return void
     */
    public function __construct(Oxy_EventStore_EventPublisher_Interface $eventsPublisher)
    {
        $this->_eventsPublisher = $eventsPublisher;
    }

	/**
     * @param Oxy_Domain_Event_Container_ContainerInterface $events
     */
    public function play(Oxy_Domain_Event_Container_ContainerInterface $events)
    {
        $this->_eventsPublisher->notifyListeners($events);
    }
}