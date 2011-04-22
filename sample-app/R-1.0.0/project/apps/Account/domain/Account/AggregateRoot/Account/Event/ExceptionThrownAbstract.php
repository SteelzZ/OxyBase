<?php
/**
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
abstract class Account_Domain_Account_AggregateRoot_Account_Event_ExceptionThrownAbstract
    extends Oxy_EventStore_Event_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_message;
    
    /**
     * @var string
     */
    protected $_date;
    
    /**
     * @var string
     */
    protected $_additional;
    
	/**
     * @return string
     */
    public function getAccountGuid()
    {
        return $this->_accountGuid;
    }

	/**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    } 

	/**
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    } 
    
	/**
     * @return string
     */
    public function getAdditional()
    {
        return $this->_additional;
    } 
}