<?php
/**
 * Base Event class
 *
 * @category Oxy
 * @package Oxy_Domain
 * @author Tomas Bartkus <to.bartkus@gmail.com>
 */
interface Oxy_Domain_EventInterface
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