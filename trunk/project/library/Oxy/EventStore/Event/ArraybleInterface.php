<?php
/**
 * Domain arrayable event interface
 * Load and convert event to array
 *
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Event
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_EventStore_Event_ArrayableInterface extends Oxy_EventStore_Event_Interface
{    
    /**
     * Convert event to array
     * 
     * @return array
     */
    public function toArray();
}