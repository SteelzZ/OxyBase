<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_PasswordChanged
    extends Oxy_EventStore_Event_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_email;
       
    /**
     * @var string
     */
    protected $_encodedPassword;
    
    /**
     * @var string
     */
    protected $_date;
    
	/**
     * @return string
     */
    public function getAccountGuid()
    {
        return $this->_accountGuid;
    }

	/**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

	/**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }
    
	/**
     * @return string
     */
    public function getEncodedPassword()
    {
        return $this->_encodedPassword;
    }    
}