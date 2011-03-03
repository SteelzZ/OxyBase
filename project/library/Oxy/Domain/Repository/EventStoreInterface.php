<?php
/**
 * Domain repository interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Repository
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Domain_Repository_EventStoreInterface
{
    /**
     * Get Aggregate root by GUID
     * 
     * @param string $aggregateRootClassName
     * @param Oxy_Guid $aggregateRootGuid
     * 
     * @return Oxy_Domain_EntityInterface
     */
    public function getById($aggregateRootClassName, Oxy_Guid $aggregateRootGuid);

    /**
     * Save aggregate root
     *
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $aggregateRoot
     * 
     * @return void
     */
    public function add(Oxy_EventStore_EventProvider_EventProviderInterface $aggregateRoot);
}