<?php
/**
 * Event store domain repository
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Repository
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Domain_Repository_EventStoreAbstract 
    implements Oxy_Domain_Repository_EventStoreInterface
{
    /**
     * @var Oxy_EventStore_EventStoreInterface
     */
    protected $_eventStore;

    /**
     * @var Oxy_EventStore_EventPublisher_EventPublisherInterface
     */
    protected $_eventsPublisher;

    /**
     * Initialize repository
     *
     * @param Oxy_EventStore_EventStoreInterface $eventStore
     * @param Oxy_EventStore_EventPublisher_EventPublisherInterface $eventsPublisher
     */
    public function __construct(
        Oxy_EventStore_EventStoreInterface $eventStore,
        Oxy_EventStore_EventPublisher_EventPublisherInterface $eventsPublisher
    ) 
    {
        $this->_eventStore = $eventStore;
        $this->_eventsPublisher = $eventsPublisher;
    }

    /**
     * @see Oxy_Domain_Repository_Interface::add()
     */
    public function add(Oxy_EventStore_EventProvider_EventProviderInterface $aggregateRoot)
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
            throw new Oxy_Domain_Repository_Exception(
                sprintf('Class of this entity was not found - %s', $aggregateRootClassName)
            );
        }
        
        try{
            $this->_eventStore->getById(
                $aggregateRootGuid,
                $aggregateRoot
            );
        } catch(Exception $ex){
            throw new Oxy_Domain_Repository_Exception(
                sprintf('Could not load events on this entity - %s', $aggregateRootClassName)
            );
        }
        
        // OK return
        return $aggregateRoot;
    }
}