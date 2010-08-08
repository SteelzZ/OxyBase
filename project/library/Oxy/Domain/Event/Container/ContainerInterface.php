<?php
/**
 * Events Container Interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Event
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_Domain_Event_Container_ContainerInterface extends Countable, IteratorAggregate
{

    /**
     * Add new event
     *
     * @param Oxy_Guid $eventProviderId
     * @param Oxy_Domain_Event_Interface $event
     * @return void
     */
    public function addEvent(Oxy_Guid $eventProviderId, Oxy_Domain_Event_Interface $event);

    /**
     * Get events for custom event provider
     *
     * @param string $eventProviderId
     * @return Oxy_Domain_Event_Interface
     */
    public function get($eventProviderId);
}