<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
class MySecureAccount_Lib_Command_DoUpdateDeviceDetails 
    extends Oxy_Cqrs_Command_CommandAbstract
{    
    /**
     * @var string
     */
    private $_deviceName;
    
    /**
     * @var array
     */
    private $_newDeviceInformation;
    
    /**
     * @param string $commandName
     * @param string $guid
     * @param string $deviceName
     * @param array $newDeviceInformation
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        $deviceName, 
        array $newDeviceInformation
        
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_deviceName = $deviceName;
        $this->_newDeviceInformation = $newDeviceInformation;
    }
    
	/**
     * @return string
     */
    public function getDeviceName()
    {
        return $this->_deviceName;
    }

    /**
     * @return array
     */
    public function getNewDeviceInformation()
    {
        return $this->_newDeviceInformation;
    }
}