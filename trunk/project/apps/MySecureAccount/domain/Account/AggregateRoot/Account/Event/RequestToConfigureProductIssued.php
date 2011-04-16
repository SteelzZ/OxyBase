<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_RequestToConfigureProductIssued
    extends Oxy_EventStore_Event_ArrayableAbstract
{        
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_accountEmail;
    
    /**
     * @var string
     */    
    protected $_productGuid;
        
    /**
     * @var string
     */    
    protected $_deviceGuid;
        
    /**
     * @var string
     */    
    protected $_deviceName;
    
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
     * @var string
     */    
    protected $_date;

	/**
     * @return string
     */
    public function getAccountEmail()
    {
        return $this->_accountEmail;
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
    public function getDeviceGuid()
    {
        return $this->_deviceGuid;
    }

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
    public function getAccountGuid()
    {
        return $this->_accountGuid;
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

	/**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }
}