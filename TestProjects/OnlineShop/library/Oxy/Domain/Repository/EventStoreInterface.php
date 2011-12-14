<?php
/**
 * Domain repository interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Repository
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\Domain\Repository;
use Oxy\Domain\AggregateRoot\AggregateRootInterface;
use Oxy\EventStore\EventProvider\EventProviderInterface;

interface EventStoreInterface
{
    /**
     * Get Aggregate root by GUID
     * 
     * @param string $aggregateRootClassName
     * @param Oxy_Guid $aggregateRootGuid
     * @param string $realIdentifier
     * 
     * @return AggregateRootInterface
     */
    public function getById($aggregateRootClassName, Oxy_Guid $aggregateRootGuid, $realIdentifier);

    /**
     * Save aggregate root
     *
     * @param EventProviderInterface $aggregateRoot
     * 
     * @return void
     */
    public function add(EventProviderInterface $aggregateRoot);
}