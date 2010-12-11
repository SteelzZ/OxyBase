<?php
/**
 * Domain repository interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Repository
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
     * Save ggregate root
     *
     * @param Oxy_EventStore_EventProvider_Interface $aggregateRoot
     * 
     * @return void
     */
    public function add(Oxy_EventStore_EventProvider_Interface $aggregateRoot);
}