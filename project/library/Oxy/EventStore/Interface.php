<?php
/**
 * Event store interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 */
interface Oxy_EventStore_Interface
{
    /**
     * @param Oxy_Guid $eventProviderGuid
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     *
     * @return Oxy_EventStore_EventProvider_Interface
     */
    public function getById(
        Oxy_Guid $eventProviderGuid, 
        Oxy_EventStore_EventProvider_Interface $eventProvider
    );

    /**
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     * @return void
     */
    public function add(Oxy_EventStore_EventProvider_Interface $eventProvider);
}