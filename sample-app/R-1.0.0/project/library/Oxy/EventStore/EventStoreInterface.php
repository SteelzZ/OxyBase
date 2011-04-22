<?php
/**
 * Event store interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_EventStoreInterface
{
    /**
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     *
     * @return Oxy_EventStore_EventProvider_EventProviderInterface
     */
    public function getById(
        Oxy_Guid $eventProviderGuid, 
        Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
    );

    /**
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     * @return void
     */
    public function add(Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider);
}