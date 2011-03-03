<?php
/**
 * Event sourced
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Domain_AggregateRoot_AggregateRootInterface
    extends Oxy_Domain_EntityInterface
{
    /**
     * Register child entity event
     *
     * @param Oxy_Domain_Entity_EventSourcedInterface $childEntity
     * @param Oxy_EventStore_Event_Interface $event
     *
     * @return void
     */
    public function registerChildEntityEvent(
        Oxy_Domain_Entity_EventSourcedInterface $childEntity,
        Oxy_EventStore_Event_Interface $event
    );
}