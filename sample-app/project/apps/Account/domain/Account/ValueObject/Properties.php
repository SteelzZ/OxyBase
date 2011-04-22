<?php
class Account_Domain_Account_ValueObject_Properties 
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var array
     */
    protected $_properties;

    /**
     * @param array $properties
     */
    public function __construct(array $properties = array())
    {
        $this->_properties = $properties;
    }
    
	/**
     * @return array
     */
    public function getProperties()
    {
        return $this->_properties;
    }
    
    /**
     * @param string $propertyName
     * @return mixed
     */
    public function getProperty($propertyName)
    {
        if (isset($this->_properties[$propertyName])) {
            return $this->_properties[$propertyName];
        } else {
            return null;
        }       
    }
    
    /**
     * @param string $propertyName
     * @return mixed
     */
    public function existsProperty($propertyName)
    {
        if (isset($this->_properties[$propertyName])) {
            return true;
        } else {
            return false;
        }       
    }
}