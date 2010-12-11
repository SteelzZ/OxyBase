<?php
/**
 * Event sourcing base Aggregate Root class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_AggregateRoot
 */
abstract class Oxy_Domain_AggregateRoot_EventSourcedAbstract 
    extends Oxy_Domain_Entity_EventSourcedAbstract
{    
    /**
     * @var Oxy_Domain_Entity_EventSourcedEntitiesCollection
     */
    protected $_childEntities;
        
    /**
     * Initialize aggregate root
     * 
     * @param Oxy_Guid $guid
     */
    public function __construct(
    	Oxy_Guid $guid
    )
    {
    	parent::__construct($guid);
        $this->_childEntities = new Oxy_Domain_Entity_EventSourcedEntitiesCollection();
    }

    /**
     * Register child entity event
     *
     * @param Oxy_Domain_Entity_EventSourcedInterface $childEntity
     * @param Oxy_EventStore_Event_Interface $event
     *
     * @return void
     */
    protected function _registerChildEntityEvent(
        Oxy_Domain_Entity_EventSourcedInterface $childEntity,
        Oxy_EventStore_Event_Interface $event
    )
    {
        $this->_childEntities->set($childEntity->getGuid(), $childEntity);
        $this->_appliedEvents->addEvent($childEntity->getGuid(), $event);
    }

    /**
     * @param Oxy_EventStore_Event_Interface $event
     *
     * @return void
     */
    protected function _handleEvent(Oxy_EventStore_Event_Interface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_appliedEvents->addEvent($this->_guid, $event);
                
        // Apply event - change state
        $this->_apply($event);
    }
    
    /**
     * Load events
     *
     * @param Oxy_EventStore_Event_StorableEventsCollection $domainEvents
     */
    public function loadEvents(Oxy_EventStore_Event_StorableEventsCollection $domainEvents)
    {
        foreach ($domainEvents as $index => $storableEvent) {
            if ((string)$storableEvent->getProviderGuid() === (string)$this->_guid) {
                $this->_apply($storableEvent->getEvent());
            } else if ($this->_childEntities->exists($storableEvent->getProviderGuid())) {
                $childEntity = $this->_childEntities->get($storableEvent->getProviderGuid());
                if($childEntity instanceof Oxy_Domain_Entity_EventSourcedInterface){
                    $childEntity->loadFromHistory(
                        new Oxy_EventStore_Event_StorableEventsCollection(
                            '',
                            array(
                                $storableEvent->getProviderGuid() => $storableEvent->getEvent()
                            )
                        )
                    );
                }
            } else {
                throw new Oxy_Domain_Exception(
                    sprintf('Child entity with guid %s does not exists', $storableEvent->getProviderGuid())
                );
            }
        }
    }
}