<?php
/**
 * Events publisher interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventPublisher
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_EventPublisher_Interface
{
    /**
     * Notify listeners about events
     *
     * @param Oxy_EventStore_Event_StorableEventsCollection $events
     * @return void
     */
    public function notifyListeners(Oxy_EventStore_Event_StorableEventsCollection $events);
}