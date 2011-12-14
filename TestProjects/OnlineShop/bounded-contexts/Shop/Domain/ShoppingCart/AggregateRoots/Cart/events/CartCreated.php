<?php
/**
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
namespace OxyBase\BoundedContextA\FirstModule\AggregateRoots\Account\Events;
use Oxy\EventStore\Event\ArrayableAbstract;

class AccountActivated extends ArrayableAbstract
{    
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_emailAddress;
    
    /**
     * @var string
     */
    protected $_state;
    
    /**
     * @var string
     */
    protected $_activationDate;
    
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
    public function getState()
    {
        return $this->_state;
    }
    
	/**
     * @return string
     */
    public function getActivationDate()
    {
        return $this->_activationDate;
    }
    
	/**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }    
}