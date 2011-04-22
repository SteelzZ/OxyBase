<?php
/**
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
class Account_Domain_Account_AggregateRoot_Account_Event_PersonalDetailsChanged
    extends Oxy_EventStore_Event_ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
        
    /**
     * @var array
     */    
    protected $_personalInformation;
    
    /**
     * @var string
     */      
    protected $_date;
    
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
    public function getAccountGuid()
    {
        return $this->_accountGuid;
    }

    /**
     * @return array
     */
    public function getPersonalInformation()
    {
        return $this->_personalInformation;
    }
}