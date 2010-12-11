<?php
/**
 * @category Oxy
 * @package Oxy_Domain
 * @subpackage Oxy_Domain_Event
 * @author <to.bartkus@gmail.com>
 **/
class Oxy_Domain_Event_Collection 
    extends Oxy_Collection
{
    /**
     * @param string $valueType - not used, left because of STRICT
     * @param array $collectionItems
     */
    public function __construct ($valueType = '', array $collectionItems = array())
    {
        parent::__construct('Oxy_Domain_EventInterface', $collectionItems);
    }
}