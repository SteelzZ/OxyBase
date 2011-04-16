<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_NewActivationKeyGeneratedForAccount
    extends Oxy_EventStore_Event_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_primaryEmail;
    
    /**
     * @var string
     */
    protected $_emailActivationKey;
    
    /**
     * @var string
     */
    protected $_date;
    
    /**
     * @var string
     */
    protected $_settings;
    
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
    public function getPrimaryEmail()
    {
        return $this->_primaryEmail;
    }

	/**
     * @return string
     */
    public function getEmailActivationKey()
    {
        return $this->_emailActivationKey;
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
    public function getSettings()
    {
        return $this->_settings;
    }	 
}