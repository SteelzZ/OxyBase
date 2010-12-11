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
    extends Oxy_Domain_EntityInterface,
            Oxy_EventStore_EventProvider_Interface
{
    /**
     * @return Oxy_Domain_AggregateRoot_EventSourcedAbstract
     */
    public function getAggregateRoot();
}