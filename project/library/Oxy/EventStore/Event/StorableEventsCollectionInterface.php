<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Event_StorableEventsCollectionInterface
    extends Oxy_Collection_Interface
{
    /**
     * Set collection items
     *
     * @param array $collectionItems
     */
    public function addEvents(array $collectionItems);

    /**
     * @param string $eventProviderGuid
     * @param mixed $value
     * @throws InvalidArgumentException when wrong type
     */
    public function addEvent($eventProviderGuid, $event);
}