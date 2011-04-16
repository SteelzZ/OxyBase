<?php
class MySecureAccount_Domain_Account_ValueObject_Language
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{
    /**
     * @var string
     */
    protected $_code;
    
    /**
     * @var string
     */
    protected $_title;
    
    /**
     * @param string $code
     * @param string $message
     */
    public function __construct($code, $title)
    {
        $this->_code = $code;        
        $this->_title = $title;        
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
    public function getTitle()
    {
        return $this->_title;
    }    
}