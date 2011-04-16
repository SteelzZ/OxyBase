<?php
class MySecureAccount_Lib_Service_Write_ProductsManagementService
{         
    const LOCK_DEVICE_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::LOCK_DEVICE_SERVICE;
    const UNLOCK_DEVICE_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::UNLOCK_DEVICE_SERVICE;
    const LOCATE_DEVICE_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::LOCATE_DEVICE_SERVICE;
    const CHANGE_DEVICE_PIN_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::CHANGE_DEVICE_PIN_SERVICE;
    const CHANGE_DEVICE_SETTINGS_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::CHANGE_DEVICE_SETTINGS_SERVICE;
    const UPGRADE_SUBSCRIPTION_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::UPGRADE_SUBSCRIPTION_SERVICE;
    const UPDATE_DEVICE_DETAILS_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::UPDATE_DEVICE_DETAILS_SERVICE;
    const READ_DEVICE_STATUS_SERVICE = MySecureAccount_Lib_Query_ServiceProviderConfiguration::READ_DEVICE_STATUS_SERVICE;
    
    const MY_MOBILE_PROTECTION_PRODUCT = MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_PROTECTION_PRODUCT; 
    const MY_MOBILE_THEFT_PROTECTION_PRODUCT = MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_THEFT_PROTECTION_PRODUCT; 
    const MY_THEFT_PROTECTION_PRODUCT = MySecureAccount_Lib_Query_ProductInformation::MY_THEFT_PROTECTION_PRODUCT; 
    const MY_ONLINE_BACKUP_PRODUCT = MySecureAccount_Lib_Query_ProductInformation::MY_ONLINE_BACKUP_PRODUCT; 
    const MY_INTERNET_SECURITY_GOLD = MySecureAccount_Lib_Query_ProductInformation::MY_INTERNET_SECURITY_GOLD; 
    const MY_INTERNET_SECURITY_SILVER = MySecureAccount_Lib_Query_ProductInformation::MY_INTERNET_SECURITY_SILVER; 
    const MY_INTERNET_SECURITY_BRONZE = MySecureAccount_Lib_Query_ProductInformation::MY_INTERNET_SECURITY_BRONZE; 
    const MY_FREE_ANTIVIRUS = MySecureAccount_Lib_Query_ProductInformation::MY_FREE_ANTIVIRUS; 
    const MY_PC_TUNEUP = MySecureAccount_Lib_Query_ProductInformation::MY_PC_TUNEUP; 
    const MY_USB_DRIVE = MySecureAccount_Lib_Query_ProductInformation::MY_USB_DRIVE; 
    const MY_VIP_SERVICE_AND_SUPPORT = MySecureAccount_Lib_Query_ProductInformation::MY_VIP_SERVICE_AND_SUPPORT; 
    const MY_MOBILE_VIP_SERVICE_AND_SUPPORT = MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_VIP_SERVICE_AND_SUPPORT; 
    const MY_EXTENDED_DOWNLOAD = MySecureAccount_Lib_Query_ProductInformation::MY_EXTENDED_DOWNLOAD; 
    const MY_MOBILE_PROTECTION_PREMIUM = MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_PROTECTION_PREMIUM; 
    const MY_ANDROID_PROTECTION_PREMIUM = MySecureAccount_Lib_Query_ProductInformation::MY_ANDROID_PROTECTION_PREMIUM; 
    const MY_SHIPPING = MySecureAccount_Lib_Query_ProductInformation::MY_SHIPPING; 
    
    /**
     * @var array
     */
    protected $_supportedProducts;
    
    /**
     * @var Oxy_Cqrs_Queue
     */
    protected $_globalQueue;
    
    /**
     * @var MySecureAccount_Lib_Service_Read_AccountManagementService
     */
    protected $_accountManagementReadService;
    
    /**
     * @var MySecureAccount_Lib_Query_ProductConfiguration
     */
    protected $_productConfigurationDto;
    
    /**
     * @var MySecureAccount_Lib_Query_ConfigurationInformation
     */
    protected $_configurationInformationDto;
    
    /**
     * @var MySecureAccount_Lib_Query_ServiceProviderConfiguration
     */
    protected $_serviceProviderConfigurationDataDto;
    
    /**
     * @param Oxy_Cqrs_Queue $globalQueue
     * @param MySecureAccount_Lib_Service_Read_AccountManagementService $accountManagementReadService
     * @param MySecureAccount_Lib_Query_ProductConfiguration $productConfigurationDto
     * @param MySecureAccount_Lib_Query_ConfigurationInformation $configurationInformationDto
     * @param MySecureAccount_Lib_Query_ServiceProviderConfiguration $serviceProviderConfigurationDataDto
     */
    public function __construct(
        Oxy_Cqrs_Queue $globalQueue,
        MySecureAccount_Lib_Service_Read_AccountManagementService $accountManagementReadService,
        MySecureAccount_Lib_Query_ProductConfiguration $productConfigurationDto,
        MySecureAccount_Lib_Query_ConfigurationInformation $configurationInformationDto,
        MySecureAccount_Lib_Query_ServiceProviderConfiguration $serviceProviderConfigurationDataDto
    )
    {
        $this->_globalQueue = $globalQueue;                
        $this->_accountManagementReadService = $accountManagementReadService;                
        $this->_productConfigurationDto = $productConfigurationDto;                
        $this->_configurationInformationDto = $configurationInformationDto;                
        $this->_serviceProviderConfigurationDataDto = $serviceProviderConfigurationDataDto;    
        $this->_supportedProducts = array(
             MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_PROTECTION_PRODUCT,
             MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_THEFT_PROTECTION_PRODUCT,
             MySecureAccount_Lib_Query_ProductInformation::MY_THEFT_PROTECTION_PRODUCT,
             MySecureAccount_Lib_Query_ProductInformation::MY_ONLINE_BACKUP_PRODUCT,
             MySecureAccount_Lib_Query_ProductInformation::MY_INTERNET_SECURITY_GOLD,
             MySecureAccount_Lib_Query_ProductInformation::MY_INTERNET_SECURITY_SILVER,
             MySecureAccount_Lib_Query_ProductInformation::MY_INTERNET_SECURITY_BRONZE,
             MySecureAccount_Lib_Query_ProductInformation::MY_FREE_ANTIVIRUS,
             MySecureAccount_Lib_Query_ProductInformation::MY_PC_TUNEUP,
             MySecureAccount_Lib_Query_ProductInformation::MY_USB_DRIVE,
             MySecureAccount_Lib_Query_ProductInformation::MY_VIP_SERVICE_AND_SUPPORT,
             MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_VIP_SERVICE_AND_SUPPORT,
             MySecureAccount_Lib_Query_ProductInformation::MY_EXTENDED_DOWNLOAD,
             MySecureAccount_Lib_Query_ProductInformation::MY_ANDROID_PROTECTION_PREMIUM,
             MySecureAccount_Lib_Query_ProductInformation::MY_MOBILE_PROTECTION_PREMIUM,
             MySecureAccount_Lib_Query_ProductInformation::MY_SHIPPING,
        );            
    }
       
    /**
     * Create new products in account
     * 
     * $products:
     *  index => productName
     *  data =>
     *  - product-name
     *  - title
     *  - version
     *  - duration
     *  - quantity
     *   
     * @param string $email
     * @param array $products
     */
    public function addNewProductsInAccount($email, array $products)
    {
        $accountGuid = new Oxy_Guid();
        $normalized = array();
        foreach ($products as $productData){
            if(in_array($productData['name'], $this->_supportedProducts)){
                $normalized[] = array(
                    $productData['name'],
                    $productData['title'],
                    $productData['version'],
                    $productData['duration'],
                    $productData['license'],
                    $productData['licenseType'],
                    $productData['settings'],
                );
            }
        }
        
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'MySecureAccount_Lib_Command_DoAddProductsInAccount', 
            array(
                $accountGuid,
                $email,
                $normalized
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
      
    /**
     * Configure given account given products with given provider
     * 
     * If reconfigure param is true, then it will reconfigure product
     * 
     * @param Oxy_Guid $configurationGuid
     */
    public function startProductConfiguration(Oxy_Guid $configurationGuid)
    {
        // @todo: wait for it ? because this can be processed earlier than reporting will be updated
        // implement some kind of timeout ?
        // simplest impl     
        $configurationData = $this->_configurationInformationDto->getDto(
            array(
                'configurationGuid' => (string)$configurationGuid
            )
        );
        if(isset($configurationData['_id'])){
            $configurationProperties = $this->_productConfigurationDto->getDto(
                array(
                    'productName' => $configurationData['productName'],
                    'accountGuid' => $configurationData['accountGuid'],
                    'deviceGuid' => $configurationData['forDeviceGuid'],
                    'productGuid' => $configurationData['productGuid'],
                    'results' => $configurationData['results'],
                    // Explictly set to (not-set) 
                	'action' => null,
                    'previousActionGuid' => null
                )
            );
            
            //var_dump($configurationProperties);
                        
            $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'MySecureAccount_Lib_Command_DoStartNewProductConfiguration', 
                array(
                    $configurationGuid,
                    (string)$configurationGuid,
                    $configurationProperties['flow'],
                    $configurationData['productName'],
                    array($configurationProperties)
                )
            );  
            
            $this->_globalQueue->addCommand($command);
        }
    }
      
    /**
     * @param string $configurationGuid
     * @param string $customSettingsForStep
     */
    public function executeNextConfigurationStep(
        Oxy_Guid $configurationGuid,  
        array $customSettingsForStep = array()
    )
    {
        $configurationData = $this->_configurationInformationDto->getDto(
            array(
                'configurationGuid' => (string)$configurationGuid
            )
        );
        
        // If exists and is not finished ok continue where finished with new input data
        if(
            isset($configurationData['_id']) 
            && $configurationData['configurationState'] !== MySecureAccount_Domain_Account_ValueObject_State::SERVICE_CONFIGURATION_FINISHED
        ){      
            $configurationProperties = $this->_productConfigurationDto->getDto(
                array(
                    'productName' => $configurationData['productName'],
                    'accountGuid' => $configurationData['accountGuid'],
                    'deviceGuid' => $configurationData['forDeviceGuid'],
                    'productGuid' => $configurationData['productGuid'],
                    'results' => $configurationData['results'],
                    'action' => $configurationData['nextStateName'],
                    'previousActionGuid' => $configurationData['previuosStateGuid'],
                )
            );
            
            $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'MySecureAccount_Lib_Command_DoExecuteConfigurationAction', 
                array(
                    $configurationGuid,
                    (string)$configurationGuid,
                    array(array_merge($configurationProperties, $customSettingsForStep))
                )
            );  
            
            $this->_globalQueue->addCommand($command);
        }        
    }
    
    /**
     * @param string $configurationGuid
     * @param array $results
     */
    public function processConfigurationActionResults(
        $configurationGuid,
        array $results
    )
    {       
        // Why we do this?
        // Because some providers will return things like:
        // $key = NULL 
        // this means that you can not check if key exists with isset, because
        // it will return that it is not set
        // so let's convert null values to empty string
        foreach ($results as $key => &$value){
            if(is_null($value)){
                $value = '';
            }
        }
        
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'MySecureAccount_Lib_Command_DoProcessConfigurationActionResults', 
            array(
                $configurationGuid,
                (string)$configurationGuid,
                $results
            )
        );  
        $this->_globalQueue->addCommand($command);        
    }
    
    /**
     * Process results
     *     
     * @param string $serviceBrokerGuid
     * @param string $providerName
     * @param string $serviceName
     * @param array $results
     */
    public function processServiceExecutionResults(
        $serviceGuid, 
        $providerName,
        $serviceName,
        array $results
    )
    {
        $serviceName = 'process' . $serviceName . 'Results';
        
        // Why we do this?
        // Because some providers will return things like:
        // $key = NULL 
        // this means that you can not check if key exists with isset, because
        // it will return that it is not set
        // so let's convert null values to empty string
        foreach ($results as $key => &$value){
            if(is_null($value)){
                $value = '';
            }
        }
        
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
        'MySecureAccount_Lib_Command_DoExecuteServiceCommand', 
            array(
                $serviceGuid,
                (string)$serviceGuid,
                $providerName,
                $serviceName,
                $results
            )
        );  
        
        $this->_globalQueue->addCommand($command);                
    }
    

    /**
     * @param string $accountEmailAddress
     * @param string $deviceName
     * @param string $productName
     * @param string $serviceName
     * @param Oxy_Guid $serviceRequestGuid
     * @param array $arguments
     */
    public function requestForService(
        $accountEmailAddress, 
        $deviceName,
        $productName,
        $serviceName,
        Oxy_Guid $serviceRequestGuid = null,
        array $arguments = array()
    )
    {
        $serviceProviderConfigurationData = $this->_serviceProviderConfigurationDataDto->getDto(
            array(
                'email' => $accountEmailAddress,
                'deviceName' => $deviceName,
                'productName' => $productName,
                'serviceName' => $serviceName
            )
        );
        
        if($serviceProviderConfigurationData){
            if(is_null($serviceRequestGuid)){
                $serviceRequestGuid = new Oxy_Guid();
            }
            
            $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'MySecureAccount_Lib_Command_DoExecuteServiceCommand', 
                array(
                    $serviceRequestGuid,
                    (string)$serviceRequestGuid,
                    $serviceProviderConfigurationData['providerName'],
                    $serviceName,
                    array_merge(
                        $serviceProviderConfigurationData['externalData'],
                        $arguments
                    )
                )
            );  
                        
            $this->_globalQueue->addCommand($command);  
        }            
    }
    
    /**
     * Remove products from account
     * 
     * @param string $accountEmail
     * @param array $products
     */
    public function removeProducts($accountEmail, array $products)
    {
        throw new Exception('Not implemented');  
    }
}