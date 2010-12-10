<?php
/**
 * Base Entity class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
abstract class Oxy_Domain_AbstractEntity extends Oxy_Domain_AbstractAggregateRoot
{
    /**
     * Aggregate root
     *
     * @var Oxy_Domain_AbstractAggregateRoot
     */
    protected $_aggregateRoot;

    /**
     * Return Aggregate Root
     *
     * @return Oxy_Domain_AbstractAggregateRoot
     */
    public function getAggregateRoot()
    {
        return $this->_aggregateRoot;
    }

    /**
     * @param string $guid
     * @param Oxy_Domain_AbstractAggregateRoot $aggregateRoot
     */
    public function __construct(
        $guid,
        Oxy_Domain_AbstractAggregateRoot $aggregateRoot
    ) 
    {
    	parent::__construct($guid);
        $this->_aggregateRoot = $aggregateRoot;
    }
}