<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Lib
 * @subpackage Command
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
class MySecureAccount_Lib_Command_DoChangeDeliveryAddress
    extends Oxy_Cqrs_Command_CommandAbstract
{        
    /**
     * @var array
     */
    private $_newDeliveryAddress;
    
    /**
     * @param string $commandName
     * @param string $guid
     * @param string $realIdentifier
     * @param array $newDeliveryAddress
     */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        array $newDeliveryAddress
        
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_newDeliveryAddress = $newDeliveryAddress;
    }

    /**
     * @return array
     */
    public function getNewDeliveryAddress()
    {
        return $this->_newDeliveryAddress;
    }
}