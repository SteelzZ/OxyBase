<?php
class Account_Lib_Service_Read_ProductManagementService
{    
    /**
     * @var Account_Lib_Query_ProductInformation
     */
    protected $_productInformationDtoBuilder;
    
    /**
     * @var Account_Lib_Query_ServiceProviderConfiguration
     */
    protected $_serviceProviderConfigurationDtoBuilder;
    
    /**
     * @var Account_Lib_Query_ProductsWithDevicesInformation
     */
    protected $_productsWithDevicesInformation;
    
    /**
     * @var Account_Lib_Query_DevicesWithProductsInformation
     */
    protected $_devicesWithProductsInformation;
    
    /**
     * @param Account_Lib_Query_ProductInformation $productInformationDtoBuilder
     * @param Account_Lib_Query_ServiceProviderConfiguration $serviceProviderConfigurationDtoBuilder
     * @param Account_Lib_Query_ProductsWithDevicesInformation $productsWithDevicesInformation
     * @param Account_Lib_Query_DevicesWithProductsInformation $devicesWithProductsInformation
     */
    public function __construct(
        Account_Lib_Query_ProductInformation $productInformationDtoBuilder,
        Account_Lib_Query_ServiceProviderConfiguration $serviceProviderConfigurationDtoBuilder,
        Account_Lib_Query_ProductsWithDevicesInformation $productsWithDevicesInformation,
        Account_Lib_Query_DevicesWithProductsInformation $devicesWithProductsInformation
    )
    {
        $this->_productInformationDtoBuilder = $productInformationDtoBuilder;                
        $this->_serviceProviderConfigurationDtoBuilder = $serviceProviderConfigurationDtoBuilder;                
        $this->_productsWithDevicesInformation = $productsWithDevicesInformation;                
        $this->_devicesWithProductsInformation = $devicesWithProductsInformation;                
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
    public function getProductInformation($email, $productName, $license)
    {
        return $this->_productInformationDtoBuilder->getDto(
            array(
                'email' => $email,
                'productName' => $productName,
                'license' => $license
            )
        ); 
    }
    
    /**
     * @param string $email
     * @param array $tags
     * 
     * @return array
     */
    public function getProductsWithDevicesInformation($email, array $tags = array())
    {
        return $this->_productsWithDevicesInformation->getDto(
            array(
                'email' => $email,
                'tags' => $tags,
            )
        );        
    }
    
    /**
     * @param string $email
     * @param array $tags
     * 
     * @return array
     */
    public function getDevicesWithProductsInformation($email, array $tags = array())
    {
        return $this->_devicesWithProductsInformation->getDto(
            array(
                'email' => $email,
           		'tags' => $tags
            )
        );        
    }
    
    /**
     * @param string $accountEmailAddress
     * @param string $deviceName
     * @param string $productName
     * @param string $serviceName
     * @param array $arguments
     * 
     * @return array
     */
    public function getProviderCurrentServiceInformation(
        $accountEmailAddress, 
        $deviceName,
        $productName,
        $serviceName,
        array $arguments
    )
    {
        return $this->_serviceProviderConfigurationDtoBuilder->getDto(
            array(
                'email' => $accountEmailAddress,
                'deviceName' => $deviceName,
                'productName' => $productName,
                'serviceName' => $serviceName
            )
        );     
    }
}