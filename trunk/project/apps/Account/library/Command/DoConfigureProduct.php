<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage Command
 */
class Account_Lib_Command_DoConfigureProduct
    extends Oxy_Cqrs_Command_CommandAbstract
{    
    /**
     * @var array
     */
    private $_properties;

    /**
    * @param string $commandName
    * @param string $guid
    * @param string $realIdentifier
    * @param array $properties
    */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        array $properties
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_properties = $properties;
    }
    
	/**
     * @return array
     */
    public function getProperties()
    {
        return $this->_properties;
    } 
}