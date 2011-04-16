<?php
/**
 * Account memento
 *
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Memento_Device
    extends Oxy_EventStore_Event_ArrayableAbstract 
    implements Oxy_EventStore_Storage_Memento_MementoInterface
{
    /**
     * @var string
     */    
    protected $_state;
    
    /**
     * @var string
     */    
    protected $_deviceName;
    
    /**
     * @var string
     */    
    protected $_installations;
    
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
    public function getDeviceName()
    {
        return $this->_deviceName;
    }

	/**
     * @return string
     */
    public function getInstallations()
    {
        return $this->_installations;
    }
}