<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 */
class MySecureAccount_Lib_Command_DoChangePassword
    extends Oxy_Cqrs_Command_CommandAbstract
{        
    /**
     * @var string
     */
    private $_password;
        
    /**
     * @param string $commandName
     * @param string $guid
     * @param string $realIdentifier
     * @param string $password
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        $password
        
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_password = $password;
    }
    
	/**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }
}