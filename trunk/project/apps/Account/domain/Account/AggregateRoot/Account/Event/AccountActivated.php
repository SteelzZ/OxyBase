<?php
/**
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
class Account_Domain_Account_AggregateRoot_Account_Event_AccountActivated
    extends Oxy_EventStore_Event_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_emailAddress;
    
    /**
     * @var string
     */
    protected $_state;
    
    /**
     * @var string
     */
    protected $_activationDate;
    
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
    public function getState()
    {
        return $this->_state;
    }
    
	/**
     * @return string
     */
    public function getActivationDate()
    {
        return $this->_activationDate;
    }
    
	/**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }    
}