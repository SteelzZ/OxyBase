<?php
/**
 * Domain repository interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Repository
 */
interface Oxy_Domain_Repository_Interface
{
    /**
     * Get Aggregate root by ID
     *
     * Behind this aggregate root will be loaded into
     * some state, what state - events will define this
     *
     * @param Msc_Guid $aggregateRootId
     *
     * @return Msc_Domain_AggregateRoot_Abstract
     */
    public function getById(Msc_Guid $aggregateRootId);

    /**
     * Save events that aggregate root generated
     *
     * @param Msc_Domain_AggregateRoot_Abstract $aggregateRoot
     * @return void
     */
    public function add(Msc_Domain_AggregateRoot_Abstract $aggregateRoot);
}