<?php
/**
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
class Account_Domain_Account_AggregateRoot_Account_Event_UserLoggedInSuccessfully
    extends Oxy_EventStore_Event_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */    
    protected $_date;
    
    /**
     * @var string
     */    
    protected $_email;
    
    /**
     * @var string
     */    
    protected $_loginState;
    
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
    public function getDate()
    {
        return $this->_date;
    }

	/**
     * @return string
     */
    public function getLoginState()
    {
        return $this->_loginState;
    }
    
	/**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    } 
}