<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_EventStore_Event_StorableEventsCollection 
    extends Oxy_Collection
    implements Oxy_EventStore_Event_StorableEventsCollectionInterface
{
    /**
     * @param array $collectionItems
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct('Oxy_EventStore_Event_StorableEventInterface');
        $this->addEvents($collectionItems);
    }
    
	/**
     * Add collection items
     *
     * @param array $collectionItems
     */
    public function addEvents(array $collectionItems)
    {
        if (!empty($collectionItems)) {
            foreach ($collectionItems as $aggregateGuid => $events) {
                foreach ($events as $eventName => $event) {
                    $this->addEvent($aggregateGuid, $event);
                }
            }
        }
    }
    
    /**
     * Add a value into the collection
     * 
     * @param string $eventProviderGuid
     * @param mixed $value
     * @throws InvalidArgumentException when wrong type
     */
    public function addEvent($eventProviderGuid, $event)
    {
        if (!$this->isValidType($event)) {
            $currentType = get_class($event);
            throw new InvalidArgumentException(
                "Trying to add a value of wrong type {$this->_valueType} {$currentType}"
            );
        }

        $nextIndex = $this->count() + 1;
        $this->_collection[$nextIndex][(string)$eventProviderGuid] = $event;
    }
    
	/**
     * Convert collection to array
     * 
     * @return array
     */
    public function toArray()
    {
        if ($this->_isBasicType) {
            return $this->_collection;
        } else {
            $collectionArray = array();
            foreach ($this->_collection as $key => $element){
                // If this is collection of non-basic elements,
                // check if that element knows how to convert itself into array
                if (method_exists($element, 'toArray')){
                    $collectionArray[$key] = $element->toArray();
                } else {
                    foreach ($element as $childKey => $childElement){
                        $collectionArray[$key][$childKey] = $childElement;
                    }
                }
            }

            return $collectionArray;
        }
    }
}