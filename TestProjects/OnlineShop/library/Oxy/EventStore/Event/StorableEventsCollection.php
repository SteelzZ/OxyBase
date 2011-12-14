<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore\Event;
use Oxy\EventStore\Event\EventInterface;
use Oxy\Guid;
use Oxy\Collection;
use Oxy\EventStore\Event\StorableEventsCollectionInterface;
use Oxy\Collection\CollectionInterface;
use Oxy\EventStore\Event\StorableEventInterface;

class StorableEventsCollection extends Collection implements StorableEventsCollectionInterface, CollectionInterface
{
    /**
     * @param array $collectionItems
     */
    public function __construct(array $collectionItems = array())
    {
        parent::__construct('Oxy\EventStore\Event\StorableEventInterface');
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
            foreach ($collectionItems as $events) {
                $this->addEvent($collectionItems);
            }
        }
    }
    
    /**
     * Add a value into the collection
     * 
     * @param StorableEventInterface $event
     * @throws InvalidArgumentException when wrong type
     */
    public function addEvent(StorableEventInterface $event)
    {
        if (!$this->isValidType($event)) {
            $currentType = get_class($event);
            throw new \InvalidArgumentException(
                "Trying to add a value of wrong type {$this->_valueType} {$currentType}"
            );
        }

        $this->_collection[] = $event;
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