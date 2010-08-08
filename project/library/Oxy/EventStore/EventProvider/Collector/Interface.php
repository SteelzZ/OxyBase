<?php
/**
 * Event providers collector interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventProvider
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_EventProvider_Collector_Interface
{
    /**
     * Register new event provider
     *
     * @param Oxy_EventStore_EventProvider_Interface $eventProvider
     * @return unknown_type
     */
    public function registerChildEventProvider(Oxy_EventStore_EventProvider_Interface $eventProvider);
}