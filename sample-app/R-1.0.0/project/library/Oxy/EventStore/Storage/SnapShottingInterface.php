<?php
/**
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Storage
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Storage_SnapShottingInterface
{
    /**
     * @param Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider
     * @return boolean
     */
    public function isSnapShotRequired(Oxy_EventStore_EventProvider_EventProviderInterface $eventProvider);
}