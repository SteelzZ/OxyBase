<?php
/**
 * Event store interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 */
interface Oxy_EventStore_Interface
{
    /**
     * @param Oxy_Guid $eventProviderId
     * @param Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
     *
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    public function getById(Oxy_Guid $eventProviderId, Oxy_Domain_AggregateRoot_Abstract $aggregateRoot);

    /**
     * @param Oxy_Domain_AggregateRoot_Abstract $aggregateRoot
     * @return void
     */
    public function add(Oxy_Domain_AggregateRoot_Abstract $aggregateRoot);
}