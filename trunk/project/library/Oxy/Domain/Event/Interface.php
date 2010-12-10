<?php
/**
 * Domain event interface
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Event
 * @author Tomas Bartkus <tomas.bartkus@mysecuritycenter.com>
 */
interface Oxy_Domain_Event_Interface
{
    /**
     * @return string
     */
    public function getEventName();
    
    /**
     * @return array
     */
    public function toArray();
}