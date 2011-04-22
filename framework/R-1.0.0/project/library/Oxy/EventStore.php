<?php
/**
 * Event store
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
class Oxy_EventStore implements Oxy_EventStore_EventStoreInterface
{
    /**
     * @var array
     */
    private $_eventProviders;

    /**
     * @var Oxy_EventStore_Storage_StorageInterface
     */
    private $_domainEventStorage;
    
    /**
     * @var Oxy_EventStore_Storage_SnapShottingInterface
     */
    private $_snapShottingStrategy;
    
    /**
     * @var Oxy_EventStore_Storage_ConflictSolverInterface
     */
    private $_conflictSolvingStrategy;

    /**
     * @param Oxy_EventStore_Storage_StorageInterface $domainEventsStorage
     *
     * @return void
     */
    public function __construct(
        Oxy_EventStore_Storage_StorageInterface $domainEventsStorage
    )
    {
        $this->_domainEventStorage = $domainEventsStorage;
        $this->_eventProviders = array();
    }

    /**
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     *
     * @return Oxy_EventStore_EventProvider_EventProviderInterface
     */
    public function getById(
        Oxy_Guid $eventProviderGuid, 
        Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
    )
    {
        $this->_loadSnapShotIfExists($eventProviderGuid, $eventProvider);
        $this->_loadRemainingHistoryEvents($eventProviderGuid, $eventProvider);
        $eventProvider->updateVersion($this->_domainEventStorage->getVersion());
        
        return $eventProvider;
    }

    /**
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     * @return void
     */
    public function add(Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider)
    {
        $this->_eventProviders[(string)$eventProvider->getGuid()] = $eventProvider;
    }

    /**
     * Commit all events
     *
     * @return void
     */
    public function commit()
    {
        foreach ($this->_eventProviders as $eventProviderGuid => $eventProvider){
            
            // Check if there is concurrency problem
            // if so use injected strategy to solve it and save correct event provider
            if((int)$eventProvider->getVersion() !== (int)$this->_domainEventStorage->getVersion($eventProviderGuid)){
                throw new Oxy_EventStore_Storage_ConcurrencyException('Concurrency!');
                /*
                $className = get_class($eventProvider);
                $fromStorage = new $className(new Oxy_Guid($eventProviderGuid));
                $this->getById($eventProviderGuid, $fromStorage);
                
                $eventProvider = $this->_conflictSolvingStrategy->solve(
                    $eventProvider,
                    $fromStorage
                );
                */
            } 
            
            // Use injected snap shotting startegy to check should we do snap shot
            /*
            if($this->_snapShottingStrategy->isSnapShotRequired($eventProvider)){
                $this->_domainEventStorage->saveSnapShot($eventProvider);
            } 
            */
            // Save event provider events
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
     * Load snapshot and return event provider
     *
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     *
     * @return Oxy_EventStore_EventProvider_EventProviderInterface
     */
    private function _loadSnapShotIfExists(
        Oxy_Guid $eventProviderGuid,
        Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
    )
    {
        $snapShot = $this->_domainEventStorage->getSnapShot($eventProviderGuid, $eventProvider);
        if (!($snapShot instanceof Oxy_EventStore_Storage_SnapShot_SnapShotInterface)) {
            return $eventProvider;
        }
        $memento = $snapShot->getMemento();

        $eventProvider->setMemento($memento);
        return $eventProvider;
    }

    /**
     * Return aggregate root
     *
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     *
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    private function _loadRemainingHistoryEvents(
        Oxy_Guid $eventProviderGuid,
        Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
    )
    {
        $domainEvents = $this->_domainEventStorage->getEventsSinceLastSnapShot($eventProvider->getGuid());
        $eventProvider->loadEvents($domainEvents);
        return $eventProvider;
    }
}