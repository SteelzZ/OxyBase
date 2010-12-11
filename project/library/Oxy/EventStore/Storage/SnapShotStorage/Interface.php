<?php
/**
 * SnapShot storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_Storage
 */
interface Oxy_EventStore_Storage_SnapShotStorage_Interface
{
    /**
     * Get snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * 
     * @return Oxy_EventStore_Storage_SnapShot_Interface
     */
    public function getSnapShot(Oxy_Guid $eventProviderGuid);

    /**
     * Save snapshot
     *
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     * @return void
     */
    public function saveSnapShot(
        Oxy_EventStore_EventProvider_Interface $eventProvider
    );
}