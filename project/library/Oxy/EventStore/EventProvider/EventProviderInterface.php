<?php
/**
 * Event provider interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventProvider
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_EventProvider_EventProviderInterface
    extends Oxy_EventStore_Storage_Memento_Originator_OriginatorInterface
{
    /**
     * Load events
     *
     * @param Oxy_EventStore_Event_StorableEventsCollectionInterface $domainEvents
     * 
     * @return void
     */
    public function loadEvents(Oxy_EventStore_Event_StorableEventsCollectionInterface $domainEvents);

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
     * @return Oxy_EventStore_Event_StorableEventsCollectionInterface
     */
    public function getChanges();
}