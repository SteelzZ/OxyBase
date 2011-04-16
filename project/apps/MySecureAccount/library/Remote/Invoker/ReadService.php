<?php
class MySecureAccount_Lib_Remote_Invoker_ReadService
{
    /**
     * @var MySecureAccount_Lib_Service_Read_AccountManagementService
     */
    private $_accountManagementService;
    
    /**
     * @var MySecureAccount_Lib_Service_Read_ProductManagementService
     */
    private $_productManagementService;
    
    /**
     * @param MySecureAccount_Lib_Service_Read_AccountManagementService $accountManagementService
     * @param MySecureAccount_Lib_Service_Read_ProductManagementService $productManagementService
     */
    public function __construct(
        MySecureAccount_Lib_Service_Read_AccountManagementService $accountManagementService,
        MySecureAccount_Lib_Service_Read_ProductManagementService $productManagementService
    )
    {
        $this->_accountManagementService = $accountManagementService;            
        $this->_productManagementService = $productManagementService;            
    }
    
    /**
     * Return account information
     * 
     * Return:
     *  - account-inforamtion
     *  - account-owner-information
     *  - account-owner-emails
     *  - passwords
     *  
     *  - isLoged
     * 
     * @param string $emailAddress
     * 
     * @return array
     */
    public function getAccountInformation($emailAddress)
    {
        return $this->_accountManagementService->getAccountInformation($emailAddress);
    }
       
    /**
     * Return all information about customer purchases
     * 
     * Return:
     *  - Product data
     *    - info (title required)
     *    - product-name 
     *  - Licenses 
     *    - used
     *    - free
     *  - Devices (can be empty array)
     *    - name
     *    - info   
     *    
     * 
     * @param string $emailAddress
     * @param array $tags
     * 
     * @return array
     */
    public function getAccountProductsInformation($emailAddress, array $tags = array())
    {
        return $this->_productManagementService->getProductsWithDevicesInformation($emailAddress, $tags);
    }
        
    /**
     * Return all devices that belongs to custom account
     * 
     * Return:
     *  - Device data
     *    - name
     *    - info
     *  - Product data (can be empty array)
     *    - info (title required)
     *    - product-name 
     *    - license
     * 
     * @param string $emailAddress
     * @param array $tags
     * 
     * @return array
     */
    public function getAccountDevicesInformation($emailAddress, array $tags = array())
    {
      return $this->_productManagementService->getDevicesWithProductsInformation($emailAddress, $tags);
    }
    
    /**
     * Return all notification subscriptions that customers has 
     * 
     * @param string $emailAddress
     * 
     * @return array
     */
    public function getAccountNotificationsInformation($emailAddress)
    {
        throw new Exception("Not implemented");
    }
}