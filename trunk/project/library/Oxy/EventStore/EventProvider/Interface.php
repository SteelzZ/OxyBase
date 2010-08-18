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
{
    /**
     * Clear all events
     *
     * @return void
     */
    public function clear();

    /**
     * Load domain events from history
     *
     * @param Oxy_Domain_Event_Container_ContainerInterface $domainEvents
     * @return Oxy_Domain_AggregateRoot_Abstract
     */
    public function loadFromHistory(Oxy_Domain_Event_Container_ContainerInterface $domainEvents);

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
     * @return Oxy_Domain_Event_Container_ContainerInterface
     */
    public function getChanges();

    /**
     * Return GUID for event provider
     *
     * @return Oxy_Guid
     */
    public function getGuid();
}