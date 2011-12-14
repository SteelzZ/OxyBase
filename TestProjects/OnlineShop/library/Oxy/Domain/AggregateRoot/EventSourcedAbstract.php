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
namespace Oxy\Domain\AggregateRoot;

// We can't use EventSourcedAbstract name for AR because entity also has same class name so we create an alias 
// by adding one level up package name
use Oxy\Domain\Entity\EventSourcedAbstract as EntityEventSourcedAbstract;
use Oxy\Domain\AggregateRoot\AggregateRootInterface;
use Oxy\Domain\AggregateRoot\ChildEntitiesCollection;
use Oxy\Domain\AggregateRoot\ChildEntityInterface;
use Oxy\EventStore\Event\EventInterface;
use Oxy\Domain\Exception;
use Oxy\EventStore\Event\StorableEventsCollection;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\EventStore\Event\StorableEventsCollectionInterface;
use Oxy\EventStore\Event\StorableEvent;
use Oxy\Guid;

abstract class EventSourcedAbstract extends EntityEventSourcedAbstract implements AggregateRootInterface
{    
    /**
     * @var ChildEntitiesCollection
     */
    protected $_childEntities;
        
    /**
     * @return ChildEntitiesCollection
     */
    public function getChildEntities()
    {
        return $this->_childEntities;
    }
        
    /**
     * Initialize aggregate root
     * 
     * @param Guid $guid
     * @param string $realIdentifier
     */
    public function __construct(
    	Guid $guid,
    	$realIdentifier
    )
    {
    	parent::__construct($guid, $realIdentifier);
        $this->_childEntities = new ChildEntitiesCollection();
    }

    /**
     * Register child entity event
     *
     * @param ChildEntityInterface $childEntity
     * @param EventInterface $event
     *
     * @return void
     */
    public function registerChildEntityEvent(
        ChildEntityInterface $childEntity,
        EventInterface $event
    )
    {
        $this->_childEntities->set((string)$childEntity->getGuid(), $childEntity);
        $this->_appliedEvents->addEvent(
            new StorableEvent(
                $childEntity->getGuid(),
                $event
            )
        );
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    protected function _handleEvent(EventInterface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_appliedEvents->addEvent(
            new StorableEvent(
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
     * @param StorableEventsCollectionInterface $domainEvents
     */
    public function loadEvents(StorableEventsCollectionInterface $domainEvents)
    {
        foreach ($domainEvents as $index => $storableEvent) {
            $eventGuid = (string)$storableEvent->getProviderGuid();
            if ($eventGuid === (string)$this->_guid) {
                $this->_apply($storableEvent->getEvent());
            } else if ($this->_childEntities->exists($eventGuid)) {
                $childEntity = $this->_childEntities->get($eventGuid);
                if($childEntity instanceof EventProviderInterface){
                    $childEntity->loadEvents(
                        new StorableEventsCollection(
                            array(
                                $storableEvent->getProviderGuid() => $storableEvent->getEvent()
                            )
                        )
                    );
                } else {
                    throw new Exception(
                        sprintf(
                        	'Child entity must implement %s interface', 
                            'Oxy_EventStore_EventProvider_EventProviderInterface'
                        )
                    );
                }
            } else {
                throw new Exception(
                    sprintf('Child entity with guid %s does not exists', $storableEvent->getProviderGuid())
                );
            }
        }
    }
}