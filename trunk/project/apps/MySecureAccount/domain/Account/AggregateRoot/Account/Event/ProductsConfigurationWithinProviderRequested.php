<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductsConfigurationWithinProviderRequested
    extends Oxy_EventStore_Event_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var array
     */
    protected $_products;
    
    /**
     * @var string
     */
    protected $_date;
    
	/**
     * @return string
     */
    public function getAccountGuid()
    {
        return $this->_accountGuid;
    }

	/**
     * @return array
     */
    public function getProducts()
    {
        return $this->_products;
    }

	/**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }  
}