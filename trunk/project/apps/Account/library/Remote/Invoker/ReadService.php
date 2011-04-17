<?php
class Account_Lib_Remote_Invoker_ReadService
{
    /**
     * @var Account_Lib_Service_Read_AccountManagementService
     */
    private $_accountManagementService;
    
    /**
     * @var Account_Lib_Service_Read_ProductManagementService
     */
    private $_productManagementService;
    
    /**
     * @param Account_Lib_Service_Read_AccountManagementService $accountManagementService
     * @param Account_Lib_Service_Read_ProductManagementService $productManagementService
     */
    public function __construct(
        Account_Lib_Service_Read_AccountManagementService $accountManagementService,
        Account_Lib_Service_Read_ProductManagementService $productManagementService
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
}