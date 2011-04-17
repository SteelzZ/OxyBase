<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage Command
 */
class Account_Lib_Command_DoActivateAccount
    extends Oxy_Cqrs_Command_CommandAbstract
{
    /**
     * @var string
     */
    private $_activationKey;
    
    /**
    * @param string $commandName
    * @param string $guid
    * @param string $realIdentifier
    * @param string $activationKey
    */
    public function __construct($commandName, $guid, $realIdentifier, $activationKey)
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_activationKey = $activationKey;
    }
    
	/**
     * @return string
     */
    public function getActivationKey()
    {
        return $this->_activationKey;
    }
}