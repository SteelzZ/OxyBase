<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_AccountResurected
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
    protected $_password;
    
    /**
     * @var string
     */    
    protected $_passwordAgain;
    
    /**
     * @var string
     */    
    protected $_encodedPassword;
        
    /**
     * @var array
     */    
    protected $_personalInformation;
    
    /**
     * @var array
     */      
    protected $_deliveryInformation;
    
    /**
     * @var array
     */      
    protected $_settings;
    
    /**
     * @var string
     */      
    protected $_state;
    
    /**
     * @var string
     */      
    protected $_loginState;
    
    /**
     * @var array
     */      
    protected $_emailActivationKey;
    
    /**
     * @var string
     */      
    protected $_date;
    
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
    public function getPassword()
    {
        return $this->_password;
    }

	/**
     * @return string
     */
    public function getPasswordAgain()
    {
        return $this->_passwordAgain;
    }

	/**
     * @return string
     */
    public function getEncodedPassword()
    {
        return $this->_encodedPassword;
    }

	/**
     * @return array
     */
    public function getPersonalInformation()
    {
        return $this->_personalInformation;
    }

	/**
     * @return array
     */
    public function getDeliveryInformation()
    {
        return $this->_deliveryInformation;
    }

	/**
     * @return array
     */
    public function getSettings()
    {
        return $this->_settings;
    }

	/**
     * @return string
     */
    public function getState()
    {
        return $this->_state;
    }

	/**
     * @return array
     */
    public function getEmailActivationKey()
    {
        return $this->_emailActivationKey;
    }
    
	/**
     * @return string
     */
    public function getLoginState()
    {
        return $this->_loginState;
    }
}