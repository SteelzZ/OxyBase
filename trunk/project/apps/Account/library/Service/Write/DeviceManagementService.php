<?php
class Account_Lib_Service_Write_DeviceManagementService
{        
	/**
     * @var Oxy_Cqrs_Queue
     */
    protected $_globalQueue;
    
    /**
     * @var Account_Lib_Service_Read_AccountManagementService
     */
    protected $_accountManagementReadService;
    
    /**
     * @var Account_Lib_Service_Read_ProductManagementService
     */
    protected $_productManagementReadService;
    
    /**
     * @var Account_Lib_Service_Read_DeviceManagementService
     */
    protected $_deviceManagementReadService;
    
    /**
     * @param Oxy_Cqrs_Queue $globalQueue
     * @param Account_Lib_Service_Read_AccountManagementService $accountManagementReadService
     * @param Account_Lib_Service_Read_ProductManagementService $accountManagementReadService
     * @param Account_Lib_Service_Read_DeviceManagementService $accountManagementReadService
     */
    public function __construct(
        Oxy_Cqrs_Queue $globalQueue,
        Account_Lib_Service_Read_AccountManagementService $accountManagementReadService,
        Account_Lib_Service_Read_ProductManagementService $productManagementReadService,
        Account_Lib_Service_Read_DeviceManagementService $deviceManagementReadService
    )
    {
        $this->_globalQueue = $globalQueue;                
        $this->_accountManagementReadService = $accountManagementReadService;                            
        $this->_productManagementReadService = $productManagementReadService;                            
        $this->_deviceManagementReadService = $deviceManagementReadService;                            
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
      
    /**
     * Configure given account given products with given provider
     * 
     * If reconfigure param is true, then it will reconfigure product
     * with given provider
     * 
     * @param string $accountEmail
     * @param string $deviceName
     * @param string $productName
     * @param string $license
     * @param string $licenseType
     * @param array $settings
     */
    public function installProductOnDevice(
        $accountEmailAddress, 
        $deviceName, 
        $productName, 
        $license, 
        array $settings = array()
    )
    {
        $accountGuid = new Oxy_Guid();   
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoInstallProductOnDevice', 
            array(
                $accountGuid,
                $accountEmailAddress,
                $deviceName,
                $productName,
                $license,
                $settings
            )
        );       

        $this->_globalQueue->addCommand($command);  
    }
    
    /**
     * @param string $accountEmail
     * @param string $deviceName
     * @param string $productName
     * @param array $settings
     */
    public function uninstallProductFromDevice(
        $accountEmailAddress, 
        $deviceName, 
        $productName, 
        array $settings = array()
    )
    {     
        $accountGuid = new Oxy_Guid();   
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoUninstallProductFromDevice', 
            array(
                $accountGuid,
                $accountEmailAddress,
                $deviceName,
                $productName,
                $settings
            )
        );       

        $this->_globalQueue->addCommand($command); 
    }
    
    /**
     * @param string $accountEmail
     * @param string $deviceName
     * @param string $productName
     * @param array $settings
     */
    public function reinstallProductOnDevice(
        $accountEmailAddress, 
        $deviceName, 
        $productName, 
        array $settings = array()
    )
    {
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoReinstallProductOnDevice', 
            array(
                $accountGuid,
                $accountEmailAddress,
                $deviceName,
                $productName,
                $settings
            )
        );       

        $this->_globalQueue->addCommand($command);  
    }
    
    /**
     * Change device details
     * 
     * @param string $accountEmailAddress
     * @param string $deviceName
     * @param array $deviceDetails
     */
    public function changeDeviceDetails(
        $accountEmailAddress, 
        $deviceName, 
        $deviceDetails
    ) 
    {
        $normalized = array(
            'deviceName' => $deviceInformation['name'],
            'deviceTitle' => $deviceDetails['deviceTitle'],
            'deviceType' => $deviceInformation['type'],
            'operatingSystem' => $deviceDetails['operatingSystem'],
            'operatingSystemType' => $deviceDetails['operatingSystemType'],
            'settings' => $deviceInformation['settings'],
        );
         
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoUpdateDeviceDetails', 
            array(
                $accountGuid,
                $accountEmailAddress,
                $deviceName,
                $normalized
            )
        );       

        $this->_globalQueue->addCommand($command);       
    }
}