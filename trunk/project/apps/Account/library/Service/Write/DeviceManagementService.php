<?php
class Account_Lib_Service_Write_DeviceManagementService
{        
	/**
     * @var Oxy_Cqrs_Queue
     */
    protected $_globalQueue;
        
    /**
     * @param Oxy_Cqrs_Queue $globalQueue
     */
    public function __construct(
        Oxy_Cqrs_Queue $globalQueue
    )
    {
        $this->_globalQueue = $globalQueue;                                          
    }
    
    /**
     * Create new devices
     * If email address is passed it will be assigned to account
     * 
     * @param string $accountEmailAddress
     * @param array $devices
     */
    public function addNewDevicesInAccount($accountEmailAddress, array $devices)
    {
        $accountGuid = new Oxy_Guid();      
    
        $normalized = array();
        foreach ($devices as $deviceData){
            $normalized[] = array(
                'deviceName' => $deviceData['deviceName'],
                'deviceTitle' => $deviceData['deviceTitle'],
                'deviceType' => $deviceData['deviceType'],
                'operatingSystem' => $deviceData['operatingSystem'],
                'operatingSystemType' => $deviceData['operatingSystemType'],
                'settings' => $deviceData['settings'],
            );
        }
        
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoAddDevicesInAccount', 
            array(
                $accountGuid,
                $accountEmailAddress,
                $normalized
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
}