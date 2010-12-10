<?php
/**
 * Base Entity class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Entity
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
abstract class Oxy_Domain_Entity_AbstractEventSourced extends 
    Oxy_Domain_AggregateRoot_AbstractEventSourced
{
    /**
     * Aggregate root
     *
     * @var Oxy_Domain_AggregateRoot_AbstractEventSourced
     */
    protected $_aggregateRoot;

    /**
     * Return Aggregate Root
     *
     * @return Oxy_Domain_AggregateRoot_AbstractEventSourced
     */
    public function getAggregateRoot()
    {
        return $this->_aggregateRoot;
    }

    /**
     * @param string $guid
     * @param Oxy_Domain_AggregateRoot_AbstractEventSourced $aggregateRoot
     */
    public function __construct(
        $guid,
        Oxy_Domain_AggregateRoot_AbstractEventSourced $aggregateRoot
    ) 
    {
        parent::__construct($guid);
        $this->_aggregateRoot = $aggregateRoot;
    }

    /**
     * Handle event
     *
     * @param Oxy_Domain_Event_Interface $event
     * @return void
     */
    protected function _handleEvent(Oxy_Domain_Event_Interface $event)
    {
        // This should not be called when loading from history
        // because if event was applied and we are loading from history
        // just load it do not add it to applied events collection
        // Add event to to applied collection
        // those will be persisted
        $this->_aggregateRoot->_registerChildEntityEvent($this, $event);
        // Apply event - change state
        $this->_apply($event);
    }
}