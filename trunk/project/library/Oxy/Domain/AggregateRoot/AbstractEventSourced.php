<?php
/**
 * Event sourcing base Aggregate Root class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_AggregateRoot
 */
abstract class Oxy_Domain_AggregateRoot_AbstractEventSourced extends Oxy_Domain_AbstractAggregateRoot /*implements 
    Oxy_EventStore_EventProvider_Interface,
    Oxy_EventStore_Storage_Memento_Orginator_Interface,
    Oxy_EventStore_Storage_SnapShot_Interface*/
{
    /**
     * @var Oxy_Domain_Event_Collection
     */
    protected $_appliedEvents;
    
    /**
     * @var Oxy_Domain_Entity_Collection
     */
    protected $_childEntities;
    
    /**
     * @return Oxy_Domain_Event_Collection
     */
    public function getChanges()
    {
        return $this->_appliedEvents;
    }
    
    /**
     * Initialize aggregate root
     * 
     * @param string $guid
     */
    public function __construct(
    	$guid
    )
    {
    	parent::__construct($guid);
        $this->_appliedEvents = new Oxy_Domain_Event_Collection();
        $this->_childEntities = new Oxy_Domain_Entity_Collection();
        $this->_version = 0;
    }

    /**
     * Register child entity event
     *
     * @param Oxy_Domain_Entity_AbstractEventSourced $childEntity
     * @param Oxy_Domain_Event_Interface $event
     *
     * @return void
     */
    protected function _registerChildEntityEvent(
        Oxy_Domain_Entity_AbstractEventSourced $childEntity,
        Oxy_Domain_Event_Interface $event
    )
    {
        $this->_childEntities->set($childEntity->getGuid(), $childEntity);
        $this->_appliedEvents->add($childEntity->getGuid(), $event);
    }

    /**
     * Handle event
     *
     * @param Oxy_Domain_Event_Interface $event
     *
     * @return void
     */
    protected function _handleEvent(Oxy_Domain_Event_Interface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_appliedEvents->add($this->_guid, $event);
                
        // Apply event - change state
        $this->_apply($event);
    }

    /**
     * Apply event - change state
     *
     * @param Oxy_Domain_Event_Interface $event
     * @return void
     */
    protected function _apply(Oxy_Domain_Event_Interface $event)
    {
        $eventName = 'on' . $event->getEventName();
        if(method_exists($this, $eventName)){
        	call_user_func_array(array($this, $eventName), array($event));
        } else {
        	throw new Oxy_Domain_Exception(
        		sprintf('Event handler for %s does not exists', $event->getEventName())
            );
        }
    }

    /**
     * Update version
     *
     * @param integer $version
     */
    public function updateVersion($version)
    {
        $this->_version = $version;
    }

    /**
     * Load events
     *
     * @param Oxy_Domain_Event_Collection $domainEvents
     */
    public function loadFromHistory (Oxy_Domain_Event_Collection $domainEvents)
    {
        foreach ($domainEvents as $index => $events) {
            foreach ($events as $eventProviderGuid => $event) {
                if ($eventProviderGuid === $this->_guid) {
                    $this->_apply($event);
                } else if ($this->_childEntities->exists($eventProviderGuid)) {
                    $this->_childEntities->get($eventProviderGuid)->loadFromHistory(
                        new Oxy_Domain_Event_Collection(
                            '',
                            array(
                                $eventProviderGuid => $event
                            )
                        )
                    );
                } else {
                    throw new Oxy_Domain_Exception(
                        sprintf('Child entity with guid %s does not exists', $eventProviderGuid)
                    );
                }
            }
        }
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->_appliedEvents->clear();
    }
}