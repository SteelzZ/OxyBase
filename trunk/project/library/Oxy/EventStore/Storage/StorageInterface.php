<?php
/**
 * Event storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Storage_StorageInterface 
    extends Oxy_EventStore_Storage_SnapShotStorage_SnapShotStorageInterface
{
    /**
     * Get all events for event provider
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return Oxy_EventStore_Event_StorableEventsCollectionInterface
     */
    public function getAllEvents(Oxy_Guid $eventProviderGuid);

    /**
     * Get all events since last snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return Oxy_EventStore_Event_StorableEventsCollectionInterface
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
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     * @return void
     */
    public function save(Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider);

    /**
     * Return version
     *
     * @param Oxy_Guid $eventProviderGuid
     * @return integer
     */
    public function getVersion(Oxy_Guid $eventProviderGuid);
}