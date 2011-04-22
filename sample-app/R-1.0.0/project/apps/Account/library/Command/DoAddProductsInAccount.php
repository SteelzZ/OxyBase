<?php
/**
 * @category Account
 * @package Account_Lib
 * @subpackage Command
 */
class Account_Lib_Command_DoAddProductsInAccount
    extends Oxy_Cqrs_Command_CommandAbstract
{
    /**
     * @var array
     */
    private $_products;
    
    /**
    * @param string $commandName
    * @param string $guid
    * @param string $realIdentifier
    * @param array $products
    */
    public function __construct(
        $commandName, 
        $guid, 
        $realIdentifier, 
        array $products
    )
    {
        parent::__construct($commandName, $guid, $realIdentifier);
        $this->_products = $products;
    }
    
	/**
     * @return array
     */
    public function getProducts()
    {
        return $this->_products;
    }
}