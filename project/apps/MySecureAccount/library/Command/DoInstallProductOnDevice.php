<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 */
class MySecureAccount_Lib_Command_DoInstallProductOnDevice
    extends Oxy_Cqrs_Command_CommandAbstract
{        
    /**
     * @var string
     */
    private $_deviceName;
    
    /**
     * @var string
     */
    private $_productName;
    
    /**
     * @var string
     */
    private $_productLicense;
        
    /**
     * @var array
     */
    private $_settings;
    
    /**
     * @param string $commandName
     * @param string $guid
     * @param string $deviceName
     * @param string $productName
     * @param string $productLicense
     * @param array $settings
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        $deviceName, 
        $productName, 
        $productLicense, 
        array $settings
        
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_deviceName = $deviceName;
        $this->_productName = $productName;
        $this->_productLicense = $productLicense;
        $this->_settings = $settings;
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
    public function getDeviceName()
    {
        return $this->_deviceName;
    }

	/**
     * @return string
     */
    public function getProductName()
    {
        return $this->_productName;
    }

	/**
     * @return array
     */
    public function getSettings()
    {
        return $this->_settings;
    }
}