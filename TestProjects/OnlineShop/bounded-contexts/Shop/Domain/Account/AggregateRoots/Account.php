<?php
namespace Shop\Domain\Account\AggregateRoots;

use Oxy\Domain\AggregateRoot\EventSourcedAbstract;
use Oxy\EventStore\Storage\Memento\MementoInterface;
use Oxy\Guid;

use Shop\Domain\Account\ValueObjects\EmailAddress;
use Shop\Domain\Account\AggregateRoots\Account\Event\NewAccountCreated;
use Shop\Domain\Account\AggregateRoots\Account\Memento\Account as MementoAccount;

class Account extends EventSourcedAbstract
{        
    /**
     * @var EmailAddress
     */
    private $_primaryEmail;
    
    /**
     * @var DevicesCollection
     */
    private $_devices;

	/**
     * Initialize aggregate root
     * 
     * @param Guid $guid
     * @param string $realIdntifier
     */
    public function __construct(
        Guid $guid, 
        $realIdntifier
    )
    {
        parent::__construct($guid, $realIdntifier);
        $this->_primaryEmail = null;
    }
      
    /**
     * Setup new account
     * 
     * @param EmailAddress $primaryEmailAddress
     */
    public function setup(
        EmailAddress $primaryEmailAddress
    )
    {
    	$this->_handleEvent(
    		new NewAccountCreated(
            	array(
            		'accountGuid' => (string)$this->_guid,
                    'emailAddress' => (string)$primaryEmailAddress
            	)
            )
        );
    }
        
    /**
     * @param NewAccountCreated $event
     */
    protected function onNewAccountCreated(NewAccountCreated $event)
    {
        $this->_primaryEmail = new EmailAddress($event->getEmailAddress());
    }
    
    /**
     * Create snapshot
     *
     * @return MementoInterface
     */
    public function createMemento()
    {
        return new MementoAccount(
            array(
	            'primaryEmail' => (string)$this->_primaryEmail,
	            'accountGuid' => (string)$this->_guid,
	            'eventName' => 'MementoCreated',
	        )
        );
    }
        
    /**
     * Load snapshot
     *
     * @param MementoInterface $memento
     * @return void
     */
    public function setMemento(MementoInterface $memento)
    {
    	$this->_primaryEmail = new EmailAddress(
            $memento->getPrimaryEmail()
        );
                
        $this->_guid = new Oxy_Guid($memento->getAccountGuid());    	
    }
}