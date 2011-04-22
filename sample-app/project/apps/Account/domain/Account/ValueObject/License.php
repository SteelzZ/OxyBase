<?php
class Account_Domain_Account_ValueObject_License
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    const TYPE_TRIAL = 'trial';
    const TYPE_FULL = 'full';
    const TYPE_NA = 'na';
    const TYPE_UNKNOWN = 'unknown';
    
    /**
     * @var string
     */
    protected $_code;
    
    /**
     * @var string
     */
    protected $_type;
        
    /**
     * @param string $code
     * @param string $type
     */
    public function __construct($code, $type = self::TYPE_UNKNOWN)
    {
        $this->_code = $code;           
        $this->_type = $type;           
    }
    
	/**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }   
    
	/**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }   
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_code;
    }
}