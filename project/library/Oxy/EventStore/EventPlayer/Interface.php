<?php
/**
 * Event player interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventPlayer
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_EventPlayer_Interface
{
    /**
     * Clear all events
     *
     * @return void
     */
    public function play(Oxy_Domain_Event_Container_ContainerInterface $events);
}