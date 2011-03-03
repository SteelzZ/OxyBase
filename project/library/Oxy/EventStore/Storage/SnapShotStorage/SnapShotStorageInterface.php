<?php
/**
 * SnapShot storage interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Storage_SnapShotStorage_SnapShotStorageInterface
{
    /**
     * Get snapshot
     *
     * @param Oxy_Guid $eventProviderGuid
     * 
     * @return Oxy_EventStore_Storage_SnapShot_SnapShotInterface
     */
    public function getSnapShot(Oxy_Guid $eventProviderGuid);

    /**
     * Save snapshot
     *
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     * @return void
     */
    public function saveSnapShot(
        Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
    );
}