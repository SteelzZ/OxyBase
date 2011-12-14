<?php
/**
 * Event sourced entity
 * Base class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Domain\Entity;
use Oxy\Domain\Exception;
use Oxy\Domain\EntityInterface;
use Oxy\EventStore\Event\WrongStateException;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\EventStore\Event\StorableEventsCollection;
use Oxy\EventStore\Event\StorableEventsCollectionInterface;
use Oxy\EventStore\Event\StorableEvent;
use Oxy\EventStore\Event\EventInterface;
use Oxy\Guid;

abstract class EventSourcedAbstract implements EntityInterface, EventProviderInterface
                
{
    /**
     * @var Guid
     */
    protected $_guid;

    /**
     * @var integer
     */
    protected $_version;
    
    /**
     * @var StorableEventsCollection
     */
    protected $_appliedEvents;
    
    /**
     * @var string
     */
    protected $_realIdentifier;
    
	/**
     * @return StorableEventsCollection
     */
    public function getChanges()
    {
        return $this->_appliedEvents;
    }
    
	/**
     * @return integer $version
     */
    public function getVersion()
    {
        return $this->_version;
    }
    
    /**
     * @return Guid
     */
    public function getGuid()
    {
        return $this->_guid;
    }
    
	/**
     * @param Oxy_Guid $guid
     * @param string $realIdentifier
     */
    public function __construct(
        Guid $guid,
        $realIdentifier
    ) 
    {
        $this->_appliedEvents = new StorableEventsCollection();
        $this->_guid = $guid;
        $this->_realIdentifier = $realIdentifier;
    }
    
    /**
     * @see EventProviderInterface::getRealIdentifier()
     */
    public function getRealIdentifier()
    {
        return $this->_realIdentifier; 
    }
    
    /**
     * @see EventProviderInterface::getName()
     */
    public function getName()
    {
        return (string)get_class($this);        
    }
    
    /**
     * @param integer $version
     */
    public function updateVersion($version)
    {
        $this->_version = $version;
    }
    
    /**
     * @param StorableEventsCollectionInterface $domainEvents
     */
    public function loadEvents(StorableEventsCollectionInterface $domainEvents)
    {
        foreach ($domainEvents as $index => $storableEvent) {
            if ((string)$storableEvent->getProviderGuid() === (string)$this->_guid) {
                $this->_apply($storableEvent->getEvent());
            } else {
                throw new Exception(
                    sprintf(
                    	'Given event does not belong to this entity - %s [%s]', 
                        (string)$storableEvent->getProviderGuid(),
                        (string)$this->_guid
                    )
                );
            }
        }
    }
    
    /**
     * @param EventInterface $event
     * @return void
     */
    protected function _handleEvent(EventInterface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_appliedEvents->addEvent(
            new StorableEvent(
                $this->_guid,
                $event
            )
        );
        
        // Apply event - change state
        $this->_apply($event);
    }
    
	/**
     * @param EventInterface $event
     * @return void
     */
    protected function _apply(EventInterface $event)
    {
        $eventHandlerName = 'on' . $event->getEventName();
        if(method_exists($this, $eventHandlerName)){
        	call_user_func_array(array($this, $eventHandlerName), array($event));
        } else {
        	$this->_onEventHandlerNotFound($event);
        }
    }
    
    /**
     * Child classes can override this one and have their own logic
     * when event handler for given event does not exists anymore 
     * 
     * @param EventInterface $event
     * @throws Exception
     */
    protected function _onEventHandlerNotFound(EventInterface $event)
    {
        throw new Exception(
    		sprintf('Event handler for %s does not exists', $event->getEventName())
        );        
    }
    
    /**
     * @param string $where
     * @throws WrongStateException
     */
    protected function _throwWrongStateException($where, $state)
    {
        throw new WrongStateException(
            sprintf('Can not execute [%s] behaviour in current state [%s]! [%s]', $where, (string)$state, (string)$this->_guid)
        );        
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_guid;
    }
}