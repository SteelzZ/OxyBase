<?php
/**
 * Event store interface
 *
 * @category Msc
 * @package Msc_EventStore
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Msc_EventStore_Interface extends Msc_UnitOfWork_Interface
{
    /**
     * Return aggregate
     *
     * @param Msc_Guid $eventProviderId
     * @param Msc_Domain_AggregateRoot_Abstract $aggregateRoot
     *
     * @return Msc_Domain_AggregateRoot_Abstract
     */
    public function getById(Msc_Guid $eventProviderId, Msc_Domain_AggregateRoot_Abstract $aggregateRoot);

    /**
     * Add to event store aggregate root (event provider)
     *
     * @param Msc_Domain_AggregateRoot_Abstract $aggregateRoot
     * @return void
     */
    public function add(Msc_Domain_AggregateRoot_Abstract $aggregateRoot);
}