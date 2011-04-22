<?php
class Account_Lib_Remote_Invoker_ReadService
{
    /**
     * @var Account_Lib_Service_Read_AccountManagementService
     */
    private $_accountManagementService;
        
    /**
     * @param Account_Lib_Service_Read_AccountManagementService $accountManagementService
     */
    public function __construct(
        Account_Lib_Service_Read_AccountManagementService $accountManagementService
    )
    {
        $this->_accountManagementService = $accountManagementService;            
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