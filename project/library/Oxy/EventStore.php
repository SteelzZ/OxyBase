<?php
/**
 * Event store
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_EventStore implements Oxy_EventStore_Interface
{
    /**
     * @var array
     */
    private $_eventProviders;

    /**
     * @var Oxy_EventStore_Storage_Interface
     */
    private $_domainEventStorage;

    /**
     * @param Oxy_EventStore_Storage_Interface $domainEventsStorage
     *
     * @return void
     */
    public function __construct(
        Oxy_EventStore_Storage_Interface $domainEventsStorage
    )
    {
        $this->_domainEventStorage = $domainEventsStorage;
        $this->_eventProviders = array();
    }

    /**
     * Return aggregate
     *
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     *
     * @return Oxy_EventStore_EventProvider_Interface
     */
    public function getById(
        Oxy_Guid $eventProviderGuid, 
        Oxy_EventStore_EventProvider_Interface $eventProvider
    )
    {
        
        
        
        
        // Perform loading actions
        /*$eventProvider = $this->loadSnapShotIfExists($eventProviderGuid, $eventProvider);
        $eventProvider = $this->loadRemainingHistoryEvents($eventProviderGuid, $eventProvider);
        
        $eventProvider->updateVersion($this->_domainEventStorage->getVersion($eventProviderGuid));
        return $eventProvider;*/
    }

    /**
     * Add to event store aggregate root (event provider)
     *
     * @param Oxy_EventStore_EventProvider_Interface $aggregateRoot
     * @return void
     */
    public function add(Oxy_EventStore_EventProvider_Interface $eventProvider)
    {
        $this->_eventProviders[(string)$eventProvider->getGuid()] = $eventProvider;
    }

    /**
     * Commit all events
     *
     * Save somewhere
     *
     * @return void
     */
    public function commit()
    {
        
        
        /*
        foreach ($this->_eventProviders as $eventProviderGuid => $eventProvider) {
            $this->_domainEventStorage->save($eventProvider);
            unset($this->_eventProviders[$eventProviderGuid]);
        }*/
    }

    /**
     * Rollback everything
     *
     * @return void
     */
    public function rollback()
    {
        $this->_eventProviders = array();
    }

    /**
     * Load snapshot and return event provider
     *
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     *
     * @return Oxy_EventStore_EventProvider_Interface
     */
    private function loadSnapShotIfExists(
        Oxy_Guid $eventProviderGuid,
        Oxy_EventStore_EventProvider_Interface $eventProvider
    )
    {
        
        
        
        
        
        
        /*
        $snapShot = $this->_domainEventStorage->getSnapShot($eventProviderId);
        if (!($snapShot instanceof Oxy_EventStore_Storage_SnapShot_Interface)) {
            return $aggregateRoot;
        }
        $memento = $snapShot->getMemento();

        $aggregateRoot->setMemento($memento);
        return $aggregateRoot;*/
    }

    /**
     * Return aggregate root
     *
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     *
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    private function loadRemainingHistoryEvents(
        Oxy_Guid $eventProviderGuid,
        Oxy_EventStore_EventProvider_Interface $eventProvider
    )
    {
        
        
        
        
        
        
        /*
        $events = $this->_domainEventStorage->getEventsSinceLastSnapShot($eventProviderId);
        $aggregateRoot->loadFromHistory($events);
        return $aggregateRoot;
        */
    }
}