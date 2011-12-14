<?php
/**
 * Event sourced child entity
 * Base class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Domain\AggregateRoot;
use Oxy\Domain\Entity\EventSourcedAbstract;
use Oxy\Domain\AggregateRoot\ChildEntityInterface;
use Oxy\Domain\AggregateRoot\AggregateRootInterface;
use Oxy\EventStore\Event\EventInterface;

abstract class EventSourcedChildEntityAbstract
    extends EventSourcedAbstract
    implements ChildEntityInterface
{    
    /**
     * @var AggregateRootInterface
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
     * @return AggregateRootInterface
     */
    public function getAggregateRoot()
    {
        return $this->_aggregateRoot;
    }

    /**
     * @param Oxy_Guid $guid
     * @param string $guid
     * @param AggregateRootInterface $aggregateRoot
     */
    public function __construct(
        Oxy_Guid $guid,
        $realIdentifier,
        AggregateRootInterface $aggregateRoot = null
    ) 
    {
        parent::__construct($guid, $realIdentifier);
        $this->_aggregateRoot = $aggregateRoot;
    }
 
	/**
     * @param EventInterface $event
     * @return void
     */
    protected function _handleEvent(EventInterface $event)
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