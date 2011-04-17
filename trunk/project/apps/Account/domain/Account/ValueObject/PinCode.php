<?php
class Account_Domain_Account_ValueObject_PinCode
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var string
     */
    private $_code;
    
    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->_code = $code;
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
    public function __toString()
    {
        return (string)$this->_code;
    }
}