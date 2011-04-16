<?php
/**
 * Event sourced entity
 * Base class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Domain_AggregateRoot_EventSourcedChildEntityAbstract
    extends Oxy_Domain_Entity_EventSourcedAbstract
    implements Oxy_Domain_AggregateRoot_ChildEntityInterface
{    
    /**
     * @var Oxy_Domain_AggregateRoot_AggregateRootInterface
     */
    protected $_aggregateRoot;
    
    /**
     * @return Oxy_Guid
     */
    public function getGuid()
    {
        return $this->_guid;
    }

    /**
     * @return Oxy_Domain_AggregateRoot_AggregateRootInterface
     */
    public function getAggregateRoot()
    {
        return $this->_aggregateRoot;
    }

    /**
     * @param Oxy_Guid $guid
     * @param string $guid
     * @param Oxy_Domain_AggregateRoot_AggregateRootInterface $aggregateRoot
     */
    public function __construct(
        Oxy_Guid $guid,
        $realIdentifier,
        Oxy_Domain_AggregateRoot_AggregateRootInterface $aggregateRoot = null
    ) 
    {
        parent::__construct($guid, $realIdentifier);
        $this->_aggregateRoot = $aggregateRoot;
    }
 
	/**
     * @param Oxy_Domain_EventInterface $event
     * @return void
     */
    protected function _handleEvent(Oxy_EventStore_Event_EventInterface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_aggregateRoot->registerChildEntityEvent($this, $event);
        // Apply event - change state
        $this->_apply($event);
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->_guid;
    }
}