<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
class MySecureAccount_Lib_Command_DoAddDevicesInAccount
    extends Oxy_Cqrs_Command_CommandAbstract
{
    /**
     * @var array
     */
    private $_devices;

    /**
    * @param string $commandName
    * @param string $guid
    * @param string $realIdentifier
    * @param array $devices
    */
    public function __construct($commandName, $guid, $realIdentifier, $devices)
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_devices = $devices;
    }
    
	/**
     * @return array
     */
    public function getDevices()
    {
        return $this->_devices;
    } 
}