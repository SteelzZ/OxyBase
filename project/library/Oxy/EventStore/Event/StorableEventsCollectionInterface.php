<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Event_StorableEventsCollectionInterface
{
    /**
     * Set collection items
     *
     * @param array $collectionItems
     */
    public function addEvents(array $collectionItems);

    /**
     * @param Oxy_EventStore_Event_StorableEventInterface $event
     */
    public function addEvent(Oxy_EventStore_Event_StorableEventInterface $event);
}