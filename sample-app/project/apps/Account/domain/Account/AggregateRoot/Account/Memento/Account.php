<?php
/**
 * Account memento
 *
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
class Account_Domain_Account_AggregateRoot_Account_Memento_Account extends Oxy_EventStore_Event_ArrayableAbstract implements 
    Oxy_EventStore_Storage_Memento_MementoInterface
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
     * @var array
     */
    protected $_currentPassword;

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
    protected $_activeProducts;

    /**
     * @var string
     */
    protected $_activationKey;

    /**
     * @var array
     */
    protected $_settings;
        
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
    public function getAccountGuid()
    {
        return $this->_accountGuid;
    }

	/**
     * @return string
     */
    public function getActivationKey()
    {
        return $this->_activationKey;
    }

    /**
     * @return field_type
     */
    public function getPrimaryEmail()
    {
        return $this->_primaryEmail;
    }

    /**
     * @return field_type
     */
    public function getCurrentPassword()
    {
        return $this->_currentPassword;
    }

    /**
     * @return field_type
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * @return field_type
     */
    public function getLoginState()
    {
        return $this->_loginState;
    }

    /**
     * @return field_type
     */
    public function getActiveProducts()
    {
        return $this->_activeProducts;
    }
}