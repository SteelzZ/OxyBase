<?php
/**
 * Event handler interface
 *
 * @category Oxy
 * @package Oxy_EventStore
 * @subpackage EventHandler
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_EventHandler_EventHandlerInterface
{
    /**
     * @param Oxy_Domain_Event_EventInterface $event
     *
     * @return void
     */
    public function handle(Oxy_EventStore_Event_EventInterface $event);
}