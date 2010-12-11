<?php
/**
 * Event storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 */
interface Oxy_EventStore_Storage_Interface extends Oxy_EventStore_Storage_SnapShotStorage_Interface
{
    /**
     * Get all events for event provider
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return Oxy_EventStore_Event_StorableEventsCollection
     */
    public function getAllEvents(Oxy_Guid $eventProviderGuid);

    /**
     * Get all events since last snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return Oxy_EventStore_Event_StorableEventsCollection
     */
    public function getEventsSinceLastSnapShot(Oxy_Guid $eventProviderGuid);

    /**
     * Get events count since last snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return integer
     */
    public function getEventCountSinceLastSnapShot(Oxy_Guid $eventProviderGuid);

    /**
     * Save event provider events
     *
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     * @return void
     */
    public function save(Oxy_EventStore_EventProvider_Interface $eventProvider);

    /**
     * Return version
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return integer
     */
    public function getVersion(Oxy_Guid $eventProviderGuid);
}