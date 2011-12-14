<?php
/**
 * Event store
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy;
use Oxy\EventStore\EventStoreInterface;
use Oxy\EventStore\Storage\StorageInterface;
use Oxy\EventStore\Storage\SnapShottingInterface;
use Oxy\EventStore\Storage\ConflictSolverInterface;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\EventStore\Storage\ConcurrencyException;
use Oxy\EventStore\Storage\SnapShot\SnapShotInterface;
use Oxy\Domain\AggregateRoot\EventSourcedAbstract;
use Oxy\Guid;

class Oxy_EventStore implements EventStoreInterface
{
    /**
     * @var array
     */
    private $_eventProviders;

    /**
     * @var StorageInterface
     */
    private $_domainEventStorage;
    
    /**
     * @var SnapShottingInterface
     */
    private $_snapShottingStrategy;
    
    /**
     * @var ConflictSolverInterface
     */
    private $_conflictSolvingStrategy;

    /**
     * @param StorageInterface $domainEventsStorage
     *
     * @return void
     */
    public function __construct(
        StorageInterface $domainEventsStorage
    )
    {
        $this->_domainEventStorage = $domainEventsStorage;
        $this->_eventProviders = array();
    }

    /**
     * @param Guid $eventProviderGuid
     * @param EventProviderInterface $eventProvider
     *
     * @return EventProviderInterface
     */
    public function getById(
        Guid $eventProviderGuid, 
        EventProviderInterface $eventProvider
    )
    {
        $this->_loadSnapShotIfExists($eventProviderGuid, $eventProvider);
        $this->_loadRemainingHistoryEvents($eventProviderGuid, $eventProvider);
        $eventProvider->updateVersion($this->_domainEventStorage->getVersion());
        
        return $eventProvider;
    }

    /**
     * @param EventProviderInterface $eventProvider
     * @return void
     */
    public function add(EventProviderInterface $eventProvider)
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
                throw new ConcurrencyException('Concurrency!');
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
     * @param Guid $eventProviderGuid
     * @param EventProviderInterface $eventProvider
     *
     * @return EventProviderInterface
     */
    private function _loadSnapShotIfExists(
        Guid $eventProviderGuid,
        EventProviderInterface $eventProvider
    )
    {
        $snapShot = $this->_domainEventStorage->getSnapShot($eventProviderGuid, $eventProvider);
        if (!($snapShot instanceof SnapShotInterface)) {
            return $eventProvider;
        }
        $memento = $snapShot->getMemento();

        $eventProvider->setMemento($memento);
        return $eventProvider;
    }

    /**
     * Return aggregate root
     *
     * @param Guid $eventProviderGuid
     * @param EventProviderInterface $eventProvider
     *
     * @return EventSourcedAbstract
     */
    private function _loadRemainingHistoryEvents(
        Guid $eventProviderGuid,
        EventProviderInterface $eventProvider
    )
    {
        $domainEvents = $this->_domainEventStorage->getEventsSinceLastSnapShot($eventProvider->getGuid());
        $eventProvider->loadEvents($domainEvents);
        return $eventProvider;
    }
}