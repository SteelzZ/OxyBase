<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 */
class MySecureAccount_Lib_Command_DoUpdatePersonalDetails
    extends Oxy_Cqrs_Command_CommandAbstract
{        
    /**
     * @var array
     */
    private $_newPersonalInformation;
    
    /**
     * @param string $commandName
     * @param string $guid
     * @param array $newPersonalInformation
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        array $newPersonalInformation
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_newPersonalInformation = $newPersonalInformation;
    }

    /**
     * @return array
     */
    public function getNewPersonalInformation()
    {
        return $this->_newPersonalInformation;
    }
}