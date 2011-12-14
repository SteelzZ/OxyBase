<?php
/**
 * Event store domain repository
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Repository
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Domain\Repository;
use Oxy\Domain\Repository\EventStoreInterface;
use Oxy\EventStore\EventStoreInterface;
use Oxy\EventStore\EventPublisher\EventPublisherInterface;
use Oxy\EventStore\EventProvider\EventProviderInterface;
use Oxy\Domain\Repository\Exception;

abstract class EventStoreAbstract 
    implements EventStoreInterface
{
    /**
     * @var EventStoreInterface
     */
    protected $_eventStore;

    /**
     * @var EventPublisherInterface
     */
    protected $_eventsPublisher;

    /**
     * Initialize repository
     *
     * @param EventStoreInterface $eventStore
     * @param EventPublisherInterface $eventsPublisher
     */
    public function __construct(
        EventStoreInterface $eventStore,
        EventPublisherInterface $eventsPublisher
    ) 
    {
        $this->_eventStore = $eventStore;
        $this->_eventsPublisher = $eventsPublisher;
    }

    /**
     * @see Oxy_Domain_Repository_Interface::add()
     */
    public function add(EventProviderInterface $aggregateRoot)
    {
        $this->_eventStore->add($aggregateRoot);
        $this->_eventStore->commit();
        $this->_eventsPublisher->notifyListeners($aggregateRoot->getChanges());
    }

    /**
     * @see Oxy_Domain_Repository_Interface::getById()
     */
    public function getById($aggregateRootClassName, Oxy_Guid $aggregateRootGuid, $realIdentifer)
    {
        try{
            // State will be loaded on this object
            $aggregateRoot = new $aggregateRootClassName(
                $aggregateRootGuid,
                $realIdentifer
            );
        } catch(Exception $ex) {
            throw new Exception(
                sprintf('Class of this entity was not found - %s', $aggregateRootClassName)
            );
        }
        
        try{
            $this->_eventStore->getById(
                $aggregateRootGuid,
                $aggregateRoot
            );
        } catch(Exception $ex){
            throw new Exception(
                sprintf('Could not load events on this entity - %s', $aggregateRootClassName)
            );
        }
        
        // OK return
        return $aggregateRoot;
    }
}