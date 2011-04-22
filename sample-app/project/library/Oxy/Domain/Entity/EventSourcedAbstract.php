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
abstract class Oxy_Domain_Entity_EventSourcedAbstract
    implements Oxy_Domain_EntityInterface,
               Oxy_EventStore_EventProvider_EventProviderInterface
                
{
    /**
     * @var Oxy_Guid
     */
    protected $_guid;

    /**
     * @var integer
     */
    protected $_version;
    
    /**
     * @var Oxy_EventStore_Event_StorableEventsCollection
     */
    protected $_appliedEvents;
    
    /**
     * @var string
     */
    protected $_realIdentifier;
    
	/**
     * @return Oxy_EventStore_Event_StorableEventsCollection
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
     * @return Oxy_Guid
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
        Oxy_Guid $guid,
        $realIdentifier
    ) 
    {
        $this->_appliedEvents = new Oxy_EventStore_Event_StorableEventsCollection();
        $this->_guid = $guid;
        $this->_realIdentifier = $realIdentifier;
    }
    
    /**
     * @see Oxy_EventStore_EventProvider_EventProviderInterface::getRealIdentifier()
     */
    public function getRealIdentifier()
    {
        return $this->_realIdentifier; 
    }
    
    /**
     * @see Oxy_EventStore_EventProvider_EventProviderInterface::getName()
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
     * @param Oxy_EventStore_Event_StorableEventsCollectionInterface $domainEvents
     */
    public function loadEvents(Oxy_EventStore_Event_StorableEventsCollectionInterface $domainEvents)
    {
        foreach ($domainEvents as $index => $storableEvent) {
            if ((string)$storableEvent->getProviderGuid() === (string)$this->_guid) {
                $this->_apply($storableEvent->getEvent());
            } else {
                throw new Oxy_Domain_Exception(
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
     * @param Oxy_EventStore_Event_EventInterface $event
     * @return void
     */
    protected function _handleEvent(Oxy_EventStore_Event_EventInterface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_appliedEvents->addEvent(
            new Oxy_EventStore_Event_StorableEvent(
                $this->_guid,
                $event
            )
        );
        
        // Apply event - change state
        $this->_apply($event);
    }
    
	/**
     * @param Oxy_Domain_EventInterface $event
     * @return void
     */
    protected function _apply(Oxy_EventStore_Event_EventInterface $event)
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
     * @param Oxy_EventStore_Event_Interface $event
     * @throws Oxy_Domain_Exception
     */
    protected function _onEventHandlerNotFound(Oxy_EventStore_Event_Interface $event)
    {
        throw new Oxy_Domain_Exception(
    		sprintf('Event handler for %s does not exists', $event->getEventName())
        );        
    }
    
    /**
     * @param string $where
     * @throws Oxy_EventStore_Event_WrongStateException
     */
    protected function _throwWrongStateException($where, $state)
    {
        throw new Oxy_EventStore_Event_WrongStateException(
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