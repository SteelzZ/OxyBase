<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Event_StorableEventInterface
{
    /**
     * @return Oxy_EventStore_Event_EventInterface
     */
    public function getEvent();
    
    /**
     * @return Oxy_Guid
     */
    public function getProviderGuid();
}