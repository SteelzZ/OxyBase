<?php
class MySecureAccount_Lib_Service_Read_DeviceManagementService
{    
    /**
     * @var MySecureAccount_Lib_Query_AccountInformation
     */
    protected $_deviceInformationDtoBuilder;
    
    /**
     * @param MySecureAccount_Lib_Query_DeviceInformation $deviceInformationDtoBuilder
     */
    public function __construct(
        MySecureAccount_Lib_Query_DeviceInformation $deviceInformationDtoBuilder
    )
    {
        $this->_deviceInformationDtoBuilder = $deviceInformationDtoBuilder;                
    }
    
    /**
     * Return account information
     * 
     * @param string $email
     * @param string $productName
     * @param string $license
     * 
     * @return array
     */
    public function getDeviceInformation($email, $deviceName)
    {
        return $this->_deviceInformationDtoBuilder->getDto(
            array(
                'email' => $email,
                'deviceName' => $deviceName
            )
        ); 
    }
}