<?php
/**Event
 * Entity collection
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Event
 * @author <to.bartkus@gmail.com>
 **/
interface Oxy_Domain_Event_StorableEventsCollectionInterface
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