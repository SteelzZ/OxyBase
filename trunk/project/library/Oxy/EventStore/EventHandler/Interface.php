<?php
/**
 * Event handler interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage Oxy_EventStore_EventHandler
 */
interface Oxy_EventStore_EventHandler_Interface
{

    /**
     * @param Oxy_Domain_Event_Interface $event
     *
     * @return void
     */
    public function handle(Oxy_Domain_Event_Interface $event);
}