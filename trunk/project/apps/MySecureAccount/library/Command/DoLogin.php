<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 */
class MySecureAccount_Lib_Command_DoLogin
    extends Oxy_Cqrs_Command_CommandAbstract
{
	/**
     * @var string
     */
    private $_emailAddress;

    /**
     * @var string
     */    
    private $_password;

    /**
     * @param string $commandName
     * @param string $guid
     * @param string $emailAddress
     * @param string $password
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        $emailAddress, 
        $password
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_emailAddress = $emailAddress;
        $this->_password = $password;
    }
    
	/**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }

	/**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }
}