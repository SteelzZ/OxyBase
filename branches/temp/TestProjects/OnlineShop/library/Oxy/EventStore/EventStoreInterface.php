<?php
/**
 * Event store interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
namespace Oxy\EventStore;
use Oxy\Guid;
use Oxy\EventStore\EventProvider\EventProviderInterface;

interface EventStoreInterface
{
    /**
     * @param Guid $eventProviderGuid
     * @param EventProviderInterface $eventProvider
     *
     * @return EventProviderInterface
     */
    public function getById(
        Oxy_Guid $eventProviderGuid, 
        EventProviderInterface $eventProvider
    );

    /**
     * @param EventProviderInterface $eventProvider
     * @return void
     */
    public function add(EventProviderInterface $eventProvider);
}