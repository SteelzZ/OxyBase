<?php
class Account_Lib_Service_Write_AccountManagementService
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
     * @param Oxy_Cqrs_Queue $globalQueue
     * @param Account_Lib_Service_Read_AccountManagementService $accountManagementReadService
     */
    public function __construct(
        Oxy_Cqrs_Queue $globalQueue,
        Account_Lib_Service_Read_AccountManagementService $accountManagementReadService
    )
    {
        $this->_globalQueue = $globalQueue;                
        $this->_accountManagementReadService = $accountManagementReadService;                
    }
    
    /**
     * Create new account
     * 
     * $ownerInformation keys:
     *  - string $firstName
     *  - string $lastName
     *  - string $dateOfBirth
     *  - string $gender
     *  - string $nickName
     *  - string $mobileNumber
     *  - string $homeNumber
     *  - string $additionalInformation
     *  
     * $deliveryInformation keys:
     *  - string $country
     *  - string $city
     *  - string $postCode
     *  - string $street
     *  - string $houseNumber
     *  - string $secondAddressLine
     *  - string $thirdAddressLine
     *  - string $additionalInformation 
     *  
     * $settings keys:
     *  - locale keys:
     *    - array country 
     *       - string code
     *       - string title
     *    - array language 
     *       - string code
     *       - string title
     *  - acceptance keys:
     *    - string spam
     *    - string terms     
     * 
     * @param string $email
     * @param string $password
     * @param string $passwordAgain
     * @param array $ownerInformation
     * @param array $deliveryInformation
     * @param array $settings
     * @param boolean $passwordAutoGenerated
     * 
     * @return void
     */
    public function createNewAccount(
        $email, 
        $password, 
        $passwordAgain, 
        array $ownerInformation,
        array $deliveryInformation,
        array $settings = array(),
        $passwordAutoGenerated = false
    )
    {     
        $accountGuid = new Oxy_Guid();            
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoSetupAccount', 
            array(
                $accountGuid,
                $email,
                $email,
                $password,
                $passwordAgain,
                $passwordAutoGenerated,
                $ownerInformation,
                $deliveryInformation,
                array($settings)
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
    
    /**
     * @param string $emailAddress
     */
    public function remindPassword($emailAddress)
    {
        $accountGuid = new Oxy_Guid();            
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoRemindPassword', 
            array(
                $accountGuid,
                $emailAddress
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
    
    /**
     * @param string $emailAddress
     */
    public function remindActivationKey($emailAddress)
    {
        $accountGuid = new Oxy_Guid();            
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoRemindEmailActivationKey', 
            array(
                $accountGuid,
                $emailAddress
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
    
    /**
     * @param string $emailAddress
     * @param string $password
     */
    public function changePassword($emailAddress, $password)
    {
        $accountGuid = new Oxy_Guid();            
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoChangePassword', 
            array(
                $accountGuid,
                $emailAddress,
                $password
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
}