<?php
/**
 * SnapShot storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_Storage_SnapShotStorage_Interface
{
    /**
     * Get snapshot
     *
     * @param Oxy_Guid $eventProvider
     * @return Oxy_EventStore_Storage_SnapShot_Interface
     */
    public function getSnapShot(Oxy_Guid $eventProviderId);

    /**
     * Save snapshot
     *
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     * @return void
     */
    public function saveSnapShot(Oxy_EventStore_EventProvider_Interface $eventProvider, $lastEventId);
}