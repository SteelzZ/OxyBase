<?php
/**
 * Event provider interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventProvider
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_EventProvider_Interface
    extends Oxy_EventStore_Storage_Memento_Originator_Interface
{
    /**
     * Load events
     *
     * @param Oxy_EventStore_Event_StorableEventsCollection $domainEvents
     * 
     * @return void
     */
    public function loadEvents(Oxy_EventStore_Event_StorableEventsCollection $domainEvents);

    /**
     * Update version
     *
     * @param Integer $version
     * @return void
     */
    public function updateVersion($version);

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion();

    /**
     * Get changes
     *
     * @return Oxy_EventStore_Event_StorableEventsCollection
     */
    public function getChanges();
}