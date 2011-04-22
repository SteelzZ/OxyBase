<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Event_EventInterface
{
    /**
     * @return string
     */
    public function getEventName();
}