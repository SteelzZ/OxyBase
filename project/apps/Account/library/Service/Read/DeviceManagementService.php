<?php
class Account_Lib_Service_Read_DeviceManagementService
{    
    /**
     * @var Account_Lib_Query_AccountInformation
     */
    protected $_deviceInformationDtoBuilder;
    
    /**
     * @param Account_Lib_Query_DeviceInformation $deviceInformationDtoBuilder
     */
    public function __construct(
        Account_Lib_Query_DeviceInformation $deviceInformationDtoBuilder
    )
    {
        $this->_deviceInformationDtoBuilder = $deviceInformationDtoBuilder;                
    }
    
    /**
     * Return device information
     * 
     * @param string $email
     * @param string $deviceName
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