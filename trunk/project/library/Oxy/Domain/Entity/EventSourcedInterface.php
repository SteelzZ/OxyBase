<?php
/**
 * Event sourcing Base Entity class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Entity
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_Domain_Entity_EventSourcedInterface
    extends Oxy_Domain_EntityInterface
{
	/**
     * @return Oxy_Domain_Event_Collection
     */
    public function getChanges();

    /**
     * @return Oxy_Domain_AggregateRoot_EventSourcedAbstract
     */
    public function getAggregateRoot();

    /**
     * @param Oxy_Domain_Event_StorableEventsCollection $domainEvents
     */
    public function loadFromHistory(Oxy_Domain_Event_StorableEventsCollection $domainEvents);

    /**
     * @return void
     */
    public function clear();
}