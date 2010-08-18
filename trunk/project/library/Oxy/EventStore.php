<?php
/**
 * Event store
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
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
    public function __construct(Oxy_EventStore_Storage_Interface $domainEventsStorage)
    {
        $this->_domainEventStorage = $domainEventsStorage;
        $this->_eventProviders = array();
    }

    /**
     * Return aggregate
     *
     * @param Oxy_Guid $eventProviderId
     * @param Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
     *
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    public function getById(Oxy_Guid $eventProviderId, Oxy_Domain_AggregateRoot_Abstract $aggregateRoot)
    {
        // Perform loading actions
        $aggregateRoot = $this->loadSnapShotIfExists($eventProviderId, $aggregateRoot);
        $aggregateRoot = $this->loadRemainingHistoryEvents($eventProviderId, $aggregateRoot);
        $aggregateRoot->updateVersion($this->_domainEventStorage->getVersion($eventProviderId));
        return $aggregateRoot;
    }

    /**
     * Add to event store aggregate root (event provider)
     *
     * @param Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
     * @return void
     */
    public function add(Oxy_Domain_AggregateRoot_Abstract $aggregateRoot)
    {
        $this->_eventProviders[(string)$aggregateRoot->getGuid()] = $aggregateRoot;
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
        foreach ($this->_eventProviders as $eventProviderGuid => $eventProvider) {
            $this->_domainEventStorage->save($eventProvider);
            unset($this->_eventProviders[$eventProviderGuid]);
        }
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
     * Load snapshot and return aggregate root
     *
     * @param Oxy_Guid $eventProviderId
     * @param Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
     *
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    private function loadSnapShotIfExists(
        Oxy_Guid $eventProviderId,
        Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
    )
    {
        $snapShot = $this->_domainEventStorage->getSnapShot($eventProviderId);
        if (is_null($snapShot) || ! $snapShot) {
            return $aggregateRoot;
        }
        $memento = $snapShot->getMemento();

        $aggregateRoot->setMemento($memento);
        return $aggregateRoot;
    }

    /**
     * Return aggregate root
     *
     * @param Oxy_Guid $eventProviderId
     * @param Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
     *
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    private function loadRemainingHistoryEvents(
        Oxy_Guid $eventProviderId,
        Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
    )
    {
        $events = $this->_domainEventStorage->getEventsSinceLastSnapShot($eventProviderId);
        $aggregateRoot->loadFromHistory($events);
        return $aggregateRoot;
    }
}