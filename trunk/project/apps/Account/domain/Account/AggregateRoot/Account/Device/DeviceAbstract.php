<?php
abstract class Account_Domain_Account_Entity_Device_DeviceAbstract
    extends Oxy_Domain_Entity_EventSourcedAbstract
{
    public function setup(
        Account_Domain_Account_ValueObject_DeviceInformation $information
    )
    { 
    }
    
    public function changeDeviceDetails(
        Account_Domain_Account_ValueObject_DeviceInformation $information
    )
    {
    }
    
    abstract public function installProducts();
    abstract public function reinstallProducts();
    abstract public function uninstallProducts();
}