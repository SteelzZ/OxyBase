<?php
/**
 * Account memento
 *
 * @category MySecureAccount
 * @package MySecureAccount_Domain
 * @subpackage Account
 * @author Tomas Bartkus <tomas@mysecuritycenter.com>
 */
namespace Shop\Domain\Account\AggregateRoots\Account\Memento;
use Oxy\EventStore\Event\ArrayableAbstract;
use Oxy\EventStore\Storage\Memento\MementoInterface;
use Oxy\Migrate\MigratableInterface;

class Account extends ArrayableAbstract implements MementoInterface, MigratableInterface
{
    /**
     * @var string
     */
    protected $_accountGuid;
    
    /**
     * @var string
     */
    protected $_primaryEmail;
   
	/**
     * @return string
     */
    public function getAccountGuid()
    {
        return $this->_accountGuid;
    }

    /**
     * @return field_type
     */
    public function getPrimaryEmail()
    {
        return $this->_primaryEmail;
    }
}