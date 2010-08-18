<?php
/**
 * Base Entity class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Entity
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
abstract class Oxy_Domain_Entity_Abstract implements 
    Oxy_EventStore_Storage_Memento_Orginator_Interface
{
    /**
     * Local within aggregate root GUID
     *
     * @var Oxy_Guid
     */
    protected $_guid;

    /**
     * Aggregate root
     *
     * @var Oxy_Domain_AggregateRoot_Abstract
     */
    protected $_aggregateRoot;

    /**
     * Return Aggregate Root
     *
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    public function getAggregateRoot()
    {
        return $this->_aggregateRoot;
    }

    /**
     * Return GUID
     *
     * @return Oxy_Guid $guid
     */
    public function getGuid()
    {
        return $this->_guid;
    }
    
    /**
     * @param Oxy_Guid $guid
     * @param Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
     */
    public function __construct(
        Oxy_Guid $guid,
        Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
    ) 
    {
        $this->_guid = $guid;
        $this->_aggregateRoot = $aggregateRoot;
    }

    /**
     * Handle event
     *
     * @param Oxy_Domain_Event_Interface $event
     *
     * @return void
     */
    public function handleEvent(Oxy_Domain_Event_Interface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_aggregateRoot->registerChildEntityEvent($this, $event);
        // Apply event - change state
        $this->apply($event);
    }

    /**
     * Apply event - change state
     *
     * @param Oxy_Domain_Event_Interface $event
     * @return void
     */
    protected function apply(Oxy_Domain_Event_Interface $event)
    {
        $eventName = 'on' . $event->getEventName();
        call_user_func_array(array($this, $eventName), array($event));
    }

    /**
     * @param Oxy_Domain_Event_Container_ContainerInterface $domainEvents
     */
    public function loadFromHistory(Oxy_Domain_Event_Container_ContainerInterface $domainEvents)
    {
        foreach ($domainEvents->getIterator() as $key => $eventData) {
            $this->apply($eventData['event']);
        }
    }
}