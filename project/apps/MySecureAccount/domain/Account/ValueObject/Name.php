<?php
class MySecureAccount_Domain_Account_ValueObject_Name
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    const UNKNOWN = 'unknown';
    
	/**
     * @var string
     */
    private $_name;
    
    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_name;
    }
}