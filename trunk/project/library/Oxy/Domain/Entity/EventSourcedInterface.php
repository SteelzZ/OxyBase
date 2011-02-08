<?php
/**
 * Event sourced
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
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