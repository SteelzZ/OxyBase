<?php
/**
 * Event sourced Aggregate Root 
 * Base class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage AggregateRoot
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Domain_AggregateRoot_EventSourcedAbstract 
    extends Oxy_Domain_Entity_EventSourcedAbstract
    implements Oxy_Domain_AggregateRoot_AggregateRootInterface
{    
    /**
     * @var Oxy_Domain_AggregateRoot_ChildEntitiesCollection
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
        $this->_childEntities = new Oxy_Domain_AggregateRoot_ChildEntitiesCollection();
    }

    /**
     * Register child entity event
     *
     * @param Oxy_Domain_Entity_EventSourcedInterface $childEntity
     * @param Oxy_EventStore_Event_Interface $event
     *
     * @return void
     */
    public function registerChildEntityEvent(
        Oxy_Domain_Entity_EventSourcedInterface $childEntity,
        Oxy_EventStore_Event_Interface $event
    )
    {
        $this->_childEntities->set($childEntity->getGuid(), $childEntity);
        $this->_appliedEvents->addEvent(
            new Oxy_EventStore_Event_StorableEvent(
                $childEntity->getGuid(),
                $event
            )
        );
    }

    /**
     * @param Oxy_EventStore_Event_EventInterface $event
     *
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
     * Load events
     *
     * @param Oxy_EventStore_Event_StorableEventsCollectionInterface $domainEvents
     */
    public function loadEvents(Oxy_EventStore_Event_StorableEventsCollectionInterface $domainEvents)
    {
        foreach ($domainEvents as $index => $storableEvent) {
            $eventGuid = (string)$storableEvent->getProviderGuid();
            if ($eventGuid === (string)$this->_guid) {
                $this->_apply($storableEvent->getEvent());
            } else if ($this->_childEntities->exists($eventGuid)) {
                $childEntity = $this->_childEntities->get($eventGuid);
                if($childEntity instanceof Oxy_EventStore_EventProvider_EventProviderInterface){
                    $childEntity->loadEvents(
                        new Oxy_EventStore_Event_StorableEventsCollection(
                            array(
                                $storableEvent->getProviderGuid() => $storableEvent->getEvent()
                            )
                        )
                    );
                } else {
                    throw new Oxy_Domain_Exception(
                        sprintf(
                        	'Child entity must implement %s interface', 
                            'Oxy_EventStore_EventProvider_EventProviderInterface'
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