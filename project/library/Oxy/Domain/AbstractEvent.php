<?php
/**
 * Base Event class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Domain_AbstractEvent implements Oxy_Domain_Event_Interface
{
    /**
     * @var string
     */
    protected $_eventName;
    
    /**
     * @var array
     */
    protected $_properties;
    
    /**
     * @param string $eventName
     * @param array $properties
     */
    public function __construct($eventName, array $properties = array())
    {
        $this->_properties = $properties;
        $this->_eventName = $eventName;
    }
    
	/**
     * @see Oxy_Domain_Event_Interface::getEventName()
     */
    public function getEventName()
    {
        return $this->_eventName;
    }
    
    /**
     * @param string $propertyName
     * @return mixed
     */
    public function getProperty($propertyName)
    {
        if(isset($this->_properties[$propertyName])){
            return $this->_properties[$propertyName];
        } else {
            return null;
        }
    }
    
    /**
     * @return array
     */
    public function getProperties()
    {
        $this->_properties;
    }
    
	/** 
     * @see Oxy_Domain_Event_Interface::toArray()
     */
    public function toArray()
    {
        return array(
            'event-name' => $this->getEventName(),
            'properties' => $this->getProperties()
        );
    }
}