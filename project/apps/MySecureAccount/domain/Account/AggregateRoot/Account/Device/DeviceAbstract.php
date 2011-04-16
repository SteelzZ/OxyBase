<?php
abstract class MySecureAccount_Domain_Account_Entity_Device_DeviceAbstract
    extends Oxy_Domain_Entity_EventSourcedAbstract
{
    public function setup(
        MySecureAccount_Domain_Account_ValueObject_DeviceInformation $information
    )
    { 
    }
    
    public function changeDeviceDetails(
        MySecureAccount_Domain_Account_ValueObject_DeviceInformation $information
    )
    {
    }
    
    abstract public function installProducts();
    abstract public function reinstallProducts();
    abstract public function uninstallProducts();
}