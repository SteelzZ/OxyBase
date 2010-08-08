<?php
/**
 * Event handler interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventHandler
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_EventStore_EventHandler_Interface
{

    /**
     * Handle event
     *
     * @param Oxy_Domain_Event_Interface $event
     *
     * @return void
     */
    public function handle(Oxy_Domain_Event_Interface $event);
}