<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
class MySecureAccount_Lib_Command_DoUninstallProductFromDevice
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
     * @var array
     */
    private $_settings;
    
    /**
     * @param string $commandName
     * @param string $guid
     * @param string $deviceName
     * @param string $productName
     * @param array $settings
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        $deviceName, 
        $productName, 
        array $settings
        
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_deviceName = $deviceName;
        $this->_productName = $productName;
        $this->_settings = $settings;
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