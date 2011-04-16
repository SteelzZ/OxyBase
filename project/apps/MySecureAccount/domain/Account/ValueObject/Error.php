<?php
class MySecureAccount_Domain_Account_ValueObject_Error
{
    /**
     * @var string
     */
    private $_code;
    
    /**
     * @var string
     */
    private $_message;
    
    /**
     * @param string $code
     * @param string $message
     */
    public function __construct($code, $message)
    {
        $this->_code = $code;        
        $this->_message = $message;        
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
    public function getMessage()
    {
        return $this->_message;
    }    
}