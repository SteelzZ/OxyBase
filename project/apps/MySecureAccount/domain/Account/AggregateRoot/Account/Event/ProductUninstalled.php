<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductUninstalled
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
    protected $_productGuid;
    
    /**
     * @var string
     */
    protected $_productName;
    
    /**
     * @var string
     */
    protected $_installationGuid;
    
    /**
     * @var string
     */
    protected $_configurationGuid;
    
    /**
     * @var string
     */
    protected $_date;
          
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
    public function getConfigurationGuid()
    {
        return $this->_configurationGuid;
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
    public function getDeviceGuid()
    {
        return $this->_deviceGuid;
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
    public function getInstallationGuid()
    {
        return $this->_installationGuid;
    }

	/**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }
}