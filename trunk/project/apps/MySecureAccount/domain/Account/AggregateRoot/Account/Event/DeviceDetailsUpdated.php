<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_DeviceDetailsUpdated
    extends Oxy_EventStore_Event_ArrayableAbstract
{  
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_deviceGuid;
    
    /**
     * @var string
     */
    protected $_date;
    
    /**
     * @var array
     */
    protected $_newDetails;
    
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
    public function getDeviceGuid()
    {
        return $this->_deviceGuid;
    }

	/**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }

	/**
     * @return array
     */
    public function getNewDetails()
    {
        return $this->_newDetails;
    }
}