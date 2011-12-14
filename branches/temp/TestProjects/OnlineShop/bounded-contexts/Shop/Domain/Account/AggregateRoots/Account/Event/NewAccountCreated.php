<?php
/**
 * @category Account
 * @package Account_Domain
 * @subpackage Account
 */
namespace Shop\Domain\Account\AggregateRoots\Account\Event;
use Oxy\EventStore\Event\ArrayableAbstract;

class NewAccountCreated extends ArrayableAbstract
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
     * @return string
     */
    public function getAccountGuid()
    {
        return $this->_accountGuid;
    }
    
	/**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->_emailAddress;
    }    
}