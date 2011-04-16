<?php
/**
 * Account memento
 *
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Memento_Product 
    extends Oxy_EventStore_Event_ArrayableAbstract 
    implements Oxy_EventStore_Storage_Memento_MementoInterface
{
    /**
     * @var string
     */
    protected $_productGuid;
    
    /**
     * @var string
     */    
    protected $_productName;
    
    /**
     * @var string
     */    
    protected $_productLicense;
    
    /**
     * @var string
     */    
    protected $_productLicenseType;
    
    /**
     * @var string
     */    
    protected $_configurationRequestGuid;
    
    /**
     * @var string
     */    
    protected $_state;
       
	/**
     * @return string
     */
    public function getProductLicense()
    {
        return $this->_productLicense;
    }

	/**
     * @return string
     */
    public function getProductLicenseType()
    {
        return $this->_productLicenseType;
    }

	/**
     * @return string
     */
    public function getProductGuid()
    {
        return $this->_productGuid;
    }

	/**
     * @return string
     */
    public function getProductName()
    {
        return $this->_productName;
    }

	/**
     * @return string
     */
    public function getConfigurationRequestGuid()
    {
        return $this->_configurationRequestGuid;
    }

	/**
     * @return string
     */
    public function getState()
    {
        return $this->_state;
    }
}