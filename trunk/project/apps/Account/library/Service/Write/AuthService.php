<?php
class Account_Lib_Service_Write_AuthService
{
    /**
     * @var Oxy_Cqrs_Queue
     */
    protected $_globalQueue;
        
    /**
     * @param Oxy_Cqrs_Queue $globalQueue
     */
    public function __construct(
        Oxy_Cqrs_Queue $globalQueue
    )
    {
        $this->_globalQueue = $globalQueue;                               
    }
    
	/**
     * Activate account by confirmg primary email address
     * 
     * @param string $email
     * @param string $activationKey
     * 
     * @return void
     */
    public function activateAccount($email, $activationKey)
    {
        $accountGuid = new Oxy_Guid();            
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoActivateAccount', 
            array(
                $accountGuid, 
                $email,
                $activationKey
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
    
    /**
     * Login
     * 
     * @param string $emailAddress
     * @param string $password
     * 
     * @return void
     */
    public function login($emailAddress, $password)
    {        
        $accountGuid = new Oxy_Guid();            
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoLogin', 
            array(
                $accountGuid,
                $emailAddress, 
                $emailAddress, 
                $password
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
    
    /**
     * Logout
     * 
     * @param string $emailAddress
     * @param string $password
     * 
     * @return void
     */
    public function logout($emailAddress)
    {        
        $accountGuid = new Oxy_Guid();            
        $command = Oxy_Cqrs_Command_CommandAbstract::factory(
            'Account_Lib_Command_DoLogout', 
            array(
                $accountGuid,
                $emailAddress
            )
        );  
        
        $this->_globalQueue->addCommand($command);
    }
}