<?php
class Account_Domain_Account_ValueObject_DeviceType
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{        
    const TYPE_LAPTOP = 'laptop';
    const TYPE_MOBILE = 'mobile';
    const TYPE_UNIVERSAL = 'universal';
    
    /**
     * @var string
     */
    protected $_type;
    
    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->_type = $type;                              
    }
	/**
     * @return string
     */
    public function getType()
    {
        return (string)$this->_type;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_type;
    }
}