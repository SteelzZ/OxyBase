<?php
class MySecureAccount_Domain_Account_ValueObject_OperatingSystem
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{        
    const OS_WINDOWS = 'windows';
    const OS_MAC = 'mac';
    const OS_SYMBIAN = 'symbian';
    const OS_BLACKBERRY = 'blackberry';
    const OS_ANDROID = 'android';
    const OS_NA = 'na';
    
    const OS_TYPE_32 = '32-bit';
    const OS_TYPE_64 = '64-bit';
    const OS_TYPE_NA = 'na';
    
    /**
     * @var string
     */
    protected $_name;
    
    /**
     * @var string
     */
    protected $_type;
    
    /**
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type)
    {
        $this->_name = $name;                              
        $this->_type = $type;                              
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
    public function getName()
    {
        return $this->_name;
    }
}