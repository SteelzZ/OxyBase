<?php
class Account_Lib_Service_Read_AccountManagementService
{    
    /**
     * @var Account_Lib_Query_AccountInformation
     */
    protected $_accountInformationDtoBuilder;
    
    /**
     * @param Account_Lib_Query_AccountInformation $accountInformationDtoBuilder
     */
    public function __construct(
        Account_Lib_Query_AccountInformation $accountInformationDtoBuilder
    )
    {
        $this->_accountInformationDtoBuilder = $accountInformationDtoBuilder;                
    }
    
    /**
     * Return account information
     * 
     * @param string $email
     * 
     * @return array
     */
    public function getAccountInformation($email)
    {
        $information = $this->_accountInformationDtoBuilder->getDto(
            array(
                'email' => $email
            )
        );         
        return $information;
    }
}