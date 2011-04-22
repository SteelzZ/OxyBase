<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Entity
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Domain_AggregateRoot_ChildEntityInterface
    extends Oxy_Domain_EntityInterface
{
    /**
     * @return Oxy_Domain_AggregateRoot_AggregateRootInterface
     */
    public function getAggregateRoot();
}