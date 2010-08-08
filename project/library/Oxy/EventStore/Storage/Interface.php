<?php
/**
 * Event storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_Storage_Interface extends Oxy_EventStore_Storage_SnapShotStorage_Interface
{
    /**
     * Get all events for event provider
     *
     * @param Oxy_Guid $eventProviderId
     * @return Oxy_Collection
     */
    public function getAllEvents(Oxy_Guid $eventProviderId);

    /**
     * Get all events since last snapshot
     *
     * @param Oxy_Guid $eventProviderId
     * @return Oxy_Collection
     */
    public function getEventsSinceLastSnapShot(Oxy_Guid $eventProviderId);

    /**
     * Get events count since last snapshot
     *
     * @param $eventProviderId
     * @return integer
     */
    public function getEventCountSinceLastSnapShot(Oxy_Guid $eventProviderId);

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
     * @param Oxy_Guid $eventProviderId
     * @return integer
     */
    public function getVersion(Oxy_Guid $eventProviderId);
}