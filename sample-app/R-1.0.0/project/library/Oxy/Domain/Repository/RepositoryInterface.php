<?php
/**
 * Domain repository interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Repository
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Domain_Repository_RepositoryInterface
{
    /**
     * Get Aggregate root by GUID
     * 
     * @param string $aggregateRootClassName
     * @param Oxy_Guid $aggregateRootGuid
     * @param string $realIdentifier
     * 
     * @return Oxy_Domain_AggregateRoot_AggregateRootInterface
     */
    public function getById($aggregateRootClassName, Oxy_Guid $aggregateRootGuid, $realIdentifier);

    /**
     * Save aggregate root
     *
     * @param Oxy_Domain_AggregateRoot_AggregateRootInterface $aggregateRoot
     * 
     * @return void
     */
    public function add(Oxy_Domain_AggregateRoot_AggregateRootInterface $aggregateRoot);
}