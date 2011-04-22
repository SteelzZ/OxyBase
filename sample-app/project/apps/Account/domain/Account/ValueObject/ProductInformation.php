<?php
class Account_Domain_Account_ValueObject_ProductInformation 
    extends Oxy_Domain_ValueObject_ArrayableAbstract
{            
    const TRIAL_LICENSE_TYPE = 'trial';
    const FULL_LICENSE_TYPE = 'full';
    const LIMITED_LICENSE_TYPE = 'limited';
    
    /**
     * @var string
     */
    protected $_productName;
    
    /**
     * @var string
     */
    protected $_title;
    
    /**
     * @var string
     */
    protected $_version;
        
    /**
     * @var string
     */
    protected $_duration;
    
    /**
     * @var string
     */
    protected $_license;
    
    /**
     * @var string
     */
    protected $_licenseType;
    
    /**
     * @var array
     */
    protected $_additionalInformation;
    
    /**
     * @param string $productName
     * @param string $title
     * @param string $version
     * @param string $duration
     * @param string $licenses
     * @param string $licenseType
     * @param array $additionalInformation
     */
    public function __construct(
        $productName, 
        $title,
        $version,
        $duration,
        $license,
        $licenseType,
        $additionalInformation
    )
    {
        $this->_productName = $productName;        
        $this->_title = $title;             
        $this->_version = $version;             
        $this->_duration = $duration;             
        $this->_licenseType = $licenseType;             
        $this->_license = $license;             
        $this->_additionalInformation = $additionalInformation;             
    }
    
	/**
     * @return array
     */
    public function getLicense()
    {
        return $this->_license;
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
    public function getTitle()
    {
        return $this->_title;
    }

	/**
     * @return string
     */
    public function getVersion()
    {
        return $this->_version;
    }

	/**
     * @return string
     */
    public function getDuration()
    {
        return $this->_duration;
    }

	/**
     * @return string
     */
    public function getLicenseType()
    {
        return $this->_licenseType;
    }

	/**
     * @return array
     */
    public function getAdditionalInformation()
    {
        return $this->_additionalInformation;
    }
}