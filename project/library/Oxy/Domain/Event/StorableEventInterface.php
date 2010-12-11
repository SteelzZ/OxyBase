<?php
/**
 * Base Storable Event class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Domain_Event_StorableEventInterface
{
    /**
     * @return Oxy_Domain_EventInterface
     */
    public function getEvent();
    
    /**
     * @return Oxy_Guid
     */
    public function getProviderGuid();
}