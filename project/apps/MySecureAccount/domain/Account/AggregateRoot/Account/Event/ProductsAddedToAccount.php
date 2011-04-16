<?php
/**
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 */
class MySecureAccount_Domain_Account_AggregateRoot_Account_Event_ProductsAddedToAccount
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
     * @var string
     */
    protected $_email;
    
    /**
     * @var array
     */
    protected $_instances;
    
	/**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

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
    
	/**
     * @return array
     */
    public function getInstances()
    {
        return $this->_instances;
    }
}