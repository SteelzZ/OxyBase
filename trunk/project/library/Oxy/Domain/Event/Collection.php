<?php
/**Event
 * Entity collection
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Event
 * @author Tomas Bartkus
 **/
class Oxy_Domain_Event_Collection extends Oxy_Collection
{

    /**
     * @param string $valueType - not used, left because of STRICT
     * @param array $collectionItems
     */
    public function __construct ($valueType = '', array $collectionItems = array())
    {
        parent::__construct('Oxy_Domain_Event_Interface', $collectionItems);
    }

    /**
     * Set collection items
     *
     * @param array $collectionItems
     */
    public function setItems(array $collectionItems)
    {
        if (!empty($collectionItems)) {
            foreach ($collectionItems as $aggregateGuid => $events) {
                foreach ($events as $eventName => $event) {
                    $this->add($aggregateGuid, $event);
                }
            }
        }
    }
    
    /**
     * Set index's value
     * 
     * @param string $eventProviderGuid
     * @param string $index
     * @param mixed $value
     * @throws OutOfRangeException
     * @throws InvalidArgumentException
     */
    public function set($eventProviderGuid, $index, $event)
    {
        if (!$this->isValidType($event)) {
            throw new InvalidArgumentException('Trying to add a value of wrong type: "' . $this->_valueType . '" expected, but "' . get_class($event) . '" was given.');
        }
        $this->_collection[$index][$eventProviderGuid] = $event;
    }
    
    /**
     * Add a value into the collection
     * 
     * @param string $eventProviderGuid
     * @param mixed $value
     * @throws InvalidArgumentException when wrong type
     */
    public function add($eventProviderGuid, $event)
    {
        if (!$this->isValidType($event)) {
            $currentType = get_class($event);
            throw new InvalidArgumentException(
                "Trying to add a value of wrong type {$this->_valueType} {$currentType}"
            );
        }

        $nextIndex = $this->count() + 1;
        $this->_collection[$nextIndex][$eventProviderGuid] = $event;
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