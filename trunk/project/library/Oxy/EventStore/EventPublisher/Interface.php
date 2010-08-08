<?php
/**
 * Events publisher interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventPublisher
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_EventPublisher_Interface
{
    /**
     * Notify listeners about events
     *
     * @param Oxy_Domain_Event_Container_Interface $events
     * @return void
     */
    public function notifyListeners(Oxy_Domain_Event_Container_ContainerInterface $events);
}