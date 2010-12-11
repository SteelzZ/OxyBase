<?php
/**
 * Event sourcing Base Entity class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Entity
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
abstract class Oxy_Domain_Entity_EventSourcedAbstract
    implements Oxy_Domain_Entity_EventSourcedInterface
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
     * @var Oxy_Domain_AggregateRoot_EventSourcedAbstract
     */
    protected $_aggregateRoot;
    
    /**
     * @var Oxy_EventStore_Event_StorableEventsCollection
     */
    protected $_appliedEvents;
    
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
     * @return Oxy_Domain_AggregateRoot_EventSourcedAbstract
     */
    public function getAggregateRoot()
    {
        return $this->_aggregateRoot;
    }

    /**
     * @param Oxy_Guid $guid
     * @param Oxy_Domain_AggregateRoot_EventSourcedAbstract $aggregateRoot
     */
    public function __construct(
        Oxy_Guid $guid,
        Oxy_Domain_AggregateRoot_EventSourcedAbstract $aggregateRoot = null
    ) 
    {
        $this->_aggregateRoot = $aggregateRoot;
        $this->_appliedEvents = new Oxy_EventStore_Event_StorableEventsCollection();
        $this->_guid = $guid;
    }
    
    /**
     * @param integer $version
     */
    public function updateVersion($version)
    {
        $this->_version = $version;
    }
    
    /**
     * @param Oxy_EventStore_Event_StorableEventsCollection $domainEvents
     */
    public function loadEvents(Oxy_EventStore_Event_StorableEventsCollection $domainEvents)
    {
        foreach ($domainEvents as $index => $storableEvent) {
            if ((string)$storableEvent->getProviderGuid() === (string)$this->_guid) {
                $this->_apply($storableEvent->getEvent());
            } else {
                throw new Oxy_Domain_Exception(
                    sprintf(
                    	'Given event does not belong to this entity - %s [%s]', 
                        $storableEvent->getProviderGuid(),
                        $this->_guid
                    )
                );
            }
        }
    }
    
    /**
     * @param Oxy_Domain_EventInterface $event
     * @return void
     */
    protected function _handleEvent(Oxy_Domain_EventInterface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_aggregateRoot->_registerChildEntityEvent($this, $event);
        
        // Apply event - change state
        $this->_apply($event);
    }
    
	/**
     * @param Oxy_Domain_EventInterface $event
     * @return void
     */
    protected function _apply(Oxy_Domain_EventInterface $event)
    {
        $eventHandlerName = 'on' . $event->getEventName();
        if(method_exists($this, $eventHandlerName)){
        	call_user_func_array(array($this, $eventHandlerName), array($event));
        } else {
        	throw new Oxy_Domain_Exception(
        		sprintf('Event handler for %s does not exists', $event->getEventName())
            );
        }
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_guid;
    }
}