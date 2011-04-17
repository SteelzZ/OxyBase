<?php
class Account_Domain_Account_ValueObject_Installation
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_guid;
    
    /**
     * @var string
     */
    protected $_date;
    
    /**
     * @var string
     */
    protected $_productGuid;
    
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
    protected $_configurationGuid;

    /**
     * @param string $guid
     * @param string $productGuid
     * @param string $date
     */
    public function __construct($guid, $configurationGuid, $productGuid, $productLicense, $productLicenseType,  $date)
    {
        $this->_guid = $guid;           
        $this->_configurationGuid = $configurationGuid;           
        $this->_date = $date;  
        $this->_productGuid = $productGuid;         
        $this->_productLicense = $productLicense;         
        $this->_productLicenseType = $productLicenseType;         
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
    public function getConfigurationGuid()
    {
        return $this->_configurationGuid;
    }

	/**
     * @return string
     */
    public function getGuid()
    {
        return $this->_guid;
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
    public function getProductGuid()
    {
        return $this->_productGuid;
    }  
}