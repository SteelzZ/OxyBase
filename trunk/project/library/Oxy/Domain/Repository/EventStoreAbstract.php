<?php
/**
 * Event store domain repository
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Repository
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Domain_Repository_EventStoreAbstract implements Oxy_Domain_Repository_EventStoreInterface
{
    /**
     * @var Oxy_EventStore_Interface
     */
    protected $_eventStore;

    /**
     * @var Oxy_EventStore_EventPublisher_Interface
     */
    protected $_eventsPublisher;

    /**
     * Initialize repository
     *
     * @param Oxy_EventStore_Interface $eventStore
     * @param Oxy_EventStore_EventPublisher_Interface $eventsPublisher
     */
    public function __construct(
        Oxy_EventStore_Interface $eventStore,
        Oxy_EventStore_EventPublisher_Interface $eventsPublisher
    ) 
    {
        $this->_eventStore = $eventStore;
        $this->_eventsPublisher = $eventsPublisher;
    }

    /**
     * (non-PHPdoc)
     * @see Oxy_Domain_Repository_Interface::add()
     */
    public function add(Oxy_EventStore_EventProvider_Interface $aggregateRoot)
    {
        $this->_eventStore->add($aggregateRoot);
        $this->_eventStore->commit();
        $this->_eventsPublisher->notifyListeners($aggregateRoot->getChanges());
    }

    /**
     * (non-PHPdoc)
     * @see Oxy_Domain_Repository_Interface::getById()
     */
    public function getById($aggregateRootClassName, Oxy_Guid $aggregateRootGuid)
    {
        // State will be loaded on this object
        $aggregateRoot = new $aggregateRootClassName(
            $aggregateRootGuid
        );
        
        // Get aggregate root
        $aggregateRoot = $this->_eventStore->getById(
            $aggregateRootGuid,
            $aggregateRoot
        );
        
        return $aggregateRoot;
    }
}